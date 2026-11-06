<?php

namespace App\Services;

use App\Models\AiConversation;
use App\Models\AiMessage;
use App\Models\Campus;
use App\Models\University;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class CounselorWorkspace
{
    public function __construct(private RegistryCounselorResponder $responder) {}

    public function owned(string $id, string $ability = 'view'): AiConversation
    {
        abort_unless(auth()->check(), 404);
        $conversation = AiConversation::where('user_id', auth()->id())->findOrFail($id);
        Gate::authorize($ability, $conversation);

        return $conversation;
    }

    /** @return array<int, array<string, mixed>> */
    public function history(string $search = '', string $filter = 'all'): array
    {
        if (! auth()->check()) {
            return [];
        }

        return AiConversation::where('user_id', auth()->id())
            ->when($search !== '', fn ($query) => $query->where('title', 'like', '%'.$search.'%'))
            ->when($filter !== 'all', fn ($query) => $query->where('topic', $filter))
            ->orderByDesc('pinned_at')->orderByDesc('last_activity_at')->orderByDesc('id')->limit(50)->get()
            ->map(fn (AiConversation $conversation): array => $this->summary($conversation))->all();
    }

    /** @return array<string, mixed> */
    public function summary(AiConversation $conversation): array
    {
        return ['id' => $conversation->id, 'title' => $conversation->title, 'topic' => $conversation->topic ?? 'admissions',
            'topic_label' => ucfirst($conversation->topic ?? 'admissions'), 'summary' => $conversation->title,
            'message_count' => $conversation->message_count, 'pinned' => $conversation->pinned_at !== null,
            'created_at' => $conversation->created_at?->toIso8601String(),
            'updated_at' => $conversation->last_activity_at?->toIso8601String(),
            'group' => $conversation->pinned_at ? 'Pinned' : ($conversation->last_activity_at?->isToday() ? 'Today' : 'Earlier'),
            'url' => route('counselor.chat', $conversation->id),
            'manage_url' => route('counselor.update', $conversation->id),
            'delete_url' => route('counselor.destroy', $conversation->id)];
    }

    /** @return array<string, mixed> */
    public function thread(AiConversation $conversation, ?int $before = null): array
    {
        $messages = $conversation->messages()->when($before, fn ($query) => $query->where('id', '<', $before))
            ->orderByDesc('id')->limit(60)->get()->reverse()->values();
        $firstId = $messages->first()?->id;
        $hasOlder = $firstId && $conversation->messages()->where('id', '<', $firstId)->exists();

        return $this->summary($conversation) + [
            'messages' => $messages->map(fn (AiMessage $message): array => $this->message($message))->all(),
            'older_url' => $hasOlder ? route('counselor.chat', ['id' => $conversation->id, 'before' => $firstId]) : null,
            'viewing_older' => $before !== null,
        ];
    }

    /** @return array<string, mixed> */
    public function message(AiMessage $message): array
    {
        $outside = app(RegionalStudentCatalog::class)->requestsOutsideCoverage($message->content);
        if ($message->sender !== 'user' && $outside) {
            return ['id' => $message->id, 'role' => 'assistant', 'content' => 'Archived response outside current Punjab and Islamabad coverage. Your original conversation is preserved.', 'html' => '<p>Archived response outside current Punjab and Islamabad coverage. Your original conversation is preserved.</p>', 'citations' => [], 'inline_cards' => [], 'prompts' => [], 'timestamp' => $message->created_at?->toIso8601String(), 'confidence' => 'Archived', 'archived_unverified' => true];
        }
        $sources = collect($message->citations ?? [])->filter(fn ($source): bool => isset($source['title'], $source['status']) && $this->responder->safeUrl($source['url'] ?? null))->values()->all();
        $html = Str::markdown(mb_substr($message->content, 0, 20000), ['html_input' => 'strip', 'allow_unsafe_links' => false, 'max_nesting_level' => 20]);
        $html = preg_replace('/<img\b[^>]*>/i', '', $html);

        return ['id' => $message->id, 'role' => $message->sender === 'user' ? 'user' : 'assistant',
            'content' => $message->content, 'html' => $html, 'citations' => $sources,
            'inline_cards' => collect($message->inline_cards ?? [])->filter(fn ($card): bool => isset($card['id'], $card['profile_url'], $card['verification_status']) && University::whereKey($card['id'])->exists() && $this->responder->safeUrl($card['profile_url']) && $this->responder->safeUrl($card['compare_url'] ?? null))->map(function (array $card): array {
                $card['campuses'] = collect($card['campuses'] ?? [])->filter(fn ($campus): bool => is_array($campus) && $this->responder->safeUrl($campus['url'] ?? null) && Campus::where('university_id', $card['id'])->where('slug', basename(parse_url($campus['url'], PHP_URL_PATH)))->exists())->values()->all();

                return $card;
            })->values()->all(),
            'prompts' => $message->prompt_suggestions ?? [], 'timestamp' => $message->created_at?->toIso8601String(),
            'confidence' => 'Registry guidance', 'archived_unverified' => $message->sender !== 'user' && $sources === []];
    }

    /** @return array<string, mixed> */
    public function send(string $text, ?string $id = null, ?string $requestId = null): array
    {
        abort_unless(auth()->check(), 401);

        return DB::transaction(function () use ($text, $id, $requestId): array {
            User::whereKey(auth()->id())->lockForUpdate()->firstOrFail();
            $conversation = $id ? $this->owned($id, 'update') : null;
            if ($requestId) {
                $existing = AiMessage::where('request_id', $requestId)->whereHas('conversation', fn ($query) => $query->where('user_id', auth()->id()))->first();
                if ($existing) {
                    abort_if($existing->content !== $text || ($id && $existing->conversation_id !== $id), 409, 'This request identifier was already used for a different message.');
                    $conversation = $existing->conversation;
                    $reply = $conversation->messages()->where('id', '>', $existing->id)->where('sender', 'assistant')->orderBy('id')->firstOrFail();

                    return ['conversation' => $this->thread($conversation), 'ai_message' => $this->message($reply), 'user_message' => $this->message($existing)];
                }
            }
            $topic = Str::contains(Str::lower($text), ['compare', ' vs ']) ? 'comparisons' : (Str::contains(Str::lower($text), ['scholarship', 'financial aid']) ? 'scholarships' : 'admissions');
            if ($requestId && AiMessage::where('request_id', $requestId)->exists()) {
                abort(409, 'This request identifier cannot be reused. Send a new message.');
            }
            $conversation ??= AiConversation::create(['id' => 'conv-'.Str::uuid(), 'user_id' => auth()->id(),
                'title' => Str::limit(preg_replace('/\s+/u', ' ', $text), 80), 'topic' => $topic, 'last_activity_at' => now()]);
            $userMessage = $conversation->messages()->create(['sender' => 'user', 'content' => $text, 'request_id' => $requestId]);
            $response = $this->responder->respond($text);
            $reply = $conversation->messages()->create(['sender' => 'assistant'] + $response);
            $conversation->update(['message_count' => $conversation->messages()->count(), 'last_activity_at' => now()]);

            return ['conversation' => $this->thread($conversation), 'ai_message' => $this->message($reply), 'user_message' => $this->message($userMessage)];
        });
    }
}
