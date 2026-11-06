<?php

namespace Tests\Feature;

use App\Models\AiConversation;
use App\Models\AiMessage;
use App\Models\User;
use App\Policies\AiConversationPolicy;
use App\Services\CounselorWorkspace;
use Database\Seeders\NumlUniversitySeeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class CounselorWorkspaceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Artisan::call('migrate', ['--database' => 'sqlite', '--force' => true]);
    }

    public function test_guest_workspace_is_empty_and_does_not_expose_other_users_research(): void
    {
        $owner = User::factory()->create();
        $this->actingAs($owner);
        app(CounselorWorkspace::class)->send('Private research title');
        auth()->forgetGuards();

        $this->get('/counselor')->assertOk()->assertSee('Your future starts with')->assertSee('Sign in to send')->assertDontSee('Private research title');
        $this->postJson(route('counselor.message'), ['message' => 'Guest question'])->assertUnauthorized();
        $this->assertSame(1, AiConversation::count());
        $this->assertSame(2, AiMessage::count());
    }

    public function test_authenticated_messages_persist_in_order_and_resume_without_session_transcripts(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->postJson(route('counselor.message'), ['message' => 'Explore universities', 'request_id' => (string) Str::uuid()]);
        $response->assertOk()->assertJsonPath('success', true);
        $id = $response->json('conversation_id');

        $this->flushSession();
        $this->actingAs($user)->get(route('counselor.chat', $id))->assertOk()->assertSee('Explore universities');
        $this->assertSame($user->id, AiConversation::findOrFail($id)->user_id);
        $this->assertSame(['user', 'assistant'], AiMessage::orderBy('id')->pluck('sender')->all());
        $this->assertSame(2, AiConversation::findOrFail($id)->message_count);
    }

    public function test_new_conversation_route_does_not_automatically_resume_an_existing_thread(): void
    {
        $this->actingAs(User::factory()->create());
        app(CounselorWorkspace::class)->send('Existing research');

        $this->get(route('counselor.chat'))->assertViewHas('activeConversation', null);
    }

    public function test_other_users_cannot_read_append_rename_pin_or_delete_a_conversation(): void
    {
        $owner = User::factory()->create();
        $this->actingAs($owner);
        $thread = app(CounselorWorkspace::class)->send('Private question')['conversation'];
        $this->actingAs(User::factory()->create());

        $this->get(route('counselor.chat', $thread['id']))->assertNotFound();
        $this->postJson(route('counselor.message'), ['conversation_id' => $thread['id'], 'message' => 'Intrusion'])->assertNotFound();
        $this->patchJson(route('counselor.update', $thread['id']), ['title' => 'Hijacked', 'pinned' => true])->assertNotFound();
        $this->deleteJson(route('counselor.destroy', $thread['id']))->assertNotFound();
        $this->assertSame(2, AiMessage::count());
        $this->assertSame('Private question', AiConversation::findOrFail($thread['id'])->title);
    }

    public function test_owner_can_rename_pin_search_and_delete_a_persisted_thread(): void
    {
        $this->actingAs(User::factory()->create());
        $thread = app(CounselorWorkspace::class)->send('Campus research')['conversation'];

        $this->patchJson(route('counselor.update', $thread['id']), ['title' => 'My NUML shortlist', 'pinned' => true])
            ->assertOk()->assertJsonPath('conversation.title', 'My NUML shortlist')->assertJsonPath('conversation.pinned', true);
        $this->get(route('counselor.history', ['q' => 'NUML']))->assertOk()->assertSee('My NUML shortlist');
        $this->assertNotNull(AiConversation::findOrFail($thread['id'])->pinned_at);
        $this->deleteJson(route('counselor.destroy', $thread['id']))->assertOk();
        $this->assertSame(0, AiConversation::count());
        $this->assertSame(0, AiMessage::count());
    }

    public function test_retrying_the_same_request_does_not_duplicate_the_conversation_or_messages(): void
    {
        $this->actingAs(User::factory()->create());
        $payload = ['message' => 'Show NUML campuses', 'request_id' => (string) Str::uuid()];
        $first = $this->postJson(route('counselor.message'), $payload)->assertOk();

        $second = $this->postJson(route('counselor.message'), $payload)->assertOk();

        $this->assertSame($first->json('conversation_id'), $second->json('conversation_id'));
        $this->assertSame($first->json('ai_message.id'), $second->json('ai_message.id'));
        $this->assertSame(1, AiConversation::count());
        $this->assertSame(2, AiMessage::count());
    }

    public function test_a_reused_request_identifier_cannot_silently_change_the_message(): void
    {
        $this->actingAs(User::factory()->create());
        $requestId = (string) Str::uuid();
        $this->postJson(route('counselor.message'), ['message' => 'First question', 'request_id' => $requestId])->assertOk();

        $this->postJson(route('counselor.message'), ['message' => 'Different question', 'request_id' => $requestId])->assertConflict();
        $this->assertSame(2, AiMessage::count());
    }

    public function test_registry_cards_and_citations_use_imported_records_without_fabricating_fees(): void
    {
        $this->seed(NumlUniversitySeeder::class);
        $this->actingAs(User::factory()->create());

        $response = $this->postJson(route('counselor.message'), ['message' => 'Show NUML campuses']);

        $response->assertOk()->assertJsonPath('ai_message.inline_cards.0.slug', 'numl')
            ->assertJsonPath('ai_message.inline_cards.0.fees', 'Not verified')
            ->assertJsonPath('ai_message.inline_cards.0.eligibility', 'Not calculated');
        $this->assertCount(5, $response->json('ai_message.inline_cards.0.campuses'));
        $sources = $response->json('ai_message.citations');
        $this->assertNotEmpty($sources);
        $this->assertStringContainsString('hec.gov.pk', $sources[0]['url']);
        $this->assertStringNotContainsString('97%', $response->json('ai_message.content'));
    }

    public function test_markdown_is_rendered_without_executable_html_or_unsafe_links(): void
    {
        $this->actingAs(User::factory()->create());
        $thread = app(CounselorWorkspace::class)->send('Question');
        $message = AiMessage::findOrFail($thread['ai_message']['id']);
        $message->update(['content' => '# Heading'."\n\n".'<script>alert(1)</script>'."\n\n".'[bad](javascript:alert(1)) **Safe bold**']);

        $html = app(CounselorWorkspace::class)->message($message)['html'];

        $this->assertStringContainsString('<h1>Heading</h1>', $html);
        $this->assertStringContainsString('<strong>Safe bold</strong>', $html);
        $this->assertStringNotContainsString('<script', $html);
        $this->assertStringNotContainsString('href="javascript:', $html);
    }

    public function test_invalid_payload_is_rejected_without_writing_messages(): void
    {
        $this->actingAs(User::factory()->create());

        $this->postJson(route('counselor.message'), ['message' => str_repeat('a', 1001)])->assertUnprocessable()->assertJsonValidationErrors('message');
        $this->postJson(route('counselor.message'), ['message' => 'Hello', 'request_id' => 'not-a-uuid'])->assertUnprocessable()->assertJsonValidationErrors('request_id');
        $this->assertSame(0, AiMessage::count());
    }

    public function test_policy_requires_exact_ownership_for_every_protected_action(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $this->actingAs($owner);
        $id = app(CounselorWorkspace::class)->send('Question')['conversation']['id'];
        $conversation = AiConversation::findOrFail($id);
        $policy = new AiConversationPolicy;

        foreach (['view', 'update', 'delete'] as $ability) {
            $this->assertTrue($policy->{$ability}($owner, $conversation));
            $this->assertFalse($policy->{$ability}($other, $conversation));
        }
    }

    public function test_long_conversations_load_a_bounded_page_and_offer_earlier_messages(): void
    {
        $this->actingAs(User::factory()->create());
        $workspace = app(CounselorWorkspace::class);
        $id = null;
        for ($index = 0; $index < 32; $index++) {
            $id = $workspace->send('Question '.$index, $id)['conversation']['id'];
        }

        $thread = $workspace->thread($workspace->owned($id));

        $this->assertCount(60, $thread['messages']);
        $this->assertNotNull($thread['older_url']);
        $earlier = $workspace->thread($workspace->owned($id), $thread['messages'][0]['id']);
        $this->assertCount(4, $earlier['messages']);
        $this->assertSame('Question 0', $earlier['messages'][0]['content']);
    }

    public function test_message_endpoint_limits_repeated_requests_per_account(): void
    {
        $this->actingAs(User::factory()->create());
        $payload = ['message' => 'Hello', 'request_id' => (string) Str::uuid()];
        for ($index = 0; $index < 20; $index++) {
            $this->postJson(route('counselor.message'), $payload)->assertOk();
        }

        $this->postJson(route('counselor.message'), $payload)->assertTooManyRequests();
        $this->assertSame(2, AiMessage::count());
    }
}
