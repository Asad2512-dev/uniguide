<?php

namespace App\Http\Controllers;

use App\Models\UniversitySourceRecord;
use App\Services\CounselorService;
use App\Services\CounselorWorkspace;
use App\Services\RegionCatalog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CounselorController extends Controller
{
    public function __construct(
        protected CounselorService $counselorService
    ) {}

    /**
     * AI Counselor Home: Welcome, intelligent prompt composer, suggestions, shortcuts.
     */
    public function index(): View
    {
        return $this->chat();
    }

    public function chat(?string $id = null): View
    {
        $workspace = app(CounselorWorkspace::class);
        $before = request()->validate(['before' => 'nullable|integer|min:1'])['before'] ?? null;
        $conversation = $id ? $workspace->owned($id) : null;
        $active = $conversation ? $workspace->thread($conversation, $before ? (int) $before : null) : null;
        $history = $workspace->history();
        if ($conversation && ! collect($history)->contains('id', $conversation->id)) {
            $history = array_merge([$workspace->summary($conversation)], array_slice($history, 0, 49));
        }

        return view('pages.counselor.chat', [
            'activeConversation' => $active,
            'conversations' => $history,
        ]);
    }

    public function sendMessage(Request $request): JsonResponse|RedirectResponse
    {
        if (! $request->user()) {
            abort_if($request->expectsJson(), 401, 'Sign in to send and save a private conversation.');

            return redirect()->route('auth.login');
        }
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
            'conversation_id' => 'nullable|string|max:255',
            'request_id' => 'nullable|uuid',
        ]);
        $id = $validated['conversation_id'] ?? null;
        $result = app(CounselorWorkspace::class)->send(trim($validated['message']), $id === 'conv-new' ? null : $id, $validated['request_id'] ?? null);
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true, 'conversation_id' => $result['conversation']['id'],
                'conversation_title' => $result['conversation']['title'],
                'conversation' => $result['conversation'], 'user_message' => $result['user_message'],
                'ai_message' => $result['ai_message'],
            ]);
        }

        return redirect()->route('counselor.chat', $result['conversation']['id']);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        abort_unless($request->user(), 401);
        $conversation = app(CounselorWorkspace::class)->owned($id, 'update');
        $validated = $request->validate(['title' => 'sometimes|required|string|max:120', 'pinned' => 'sometimes|required|boolean']);
        if (isset($validated['title'])) {
            $conversation->title = $validated['title'];
        }
        if (array_key_exists('pinned', $validated)) {
            $conversation->pinned_at = $validated['pinned'] ? now() : null;
        }
        $conversation->save();

        return response()->json(['conversation' => app(CounselorWorkspace::class)->summary($conversation)]);
    }

    /**
     * Guided AI Counselor Multi-step Assessment Wizard.
     */
    public function guided(): View
    {
        return view('pages.counselor.guided', [
            'disciplines' => [
                ['id' => 'computing', 'name' => 'Computing & Artificial Intelligence', 'desc' => 'CS, Software Engineering, AI, Data Science, Cyber Security', 'icon' => 'laptop'],
                ['id' => 'engineering', 'name' => 'Engineering & Technology', 'desc' => 'Electrical, Mechanical, Civil, Mechatronics, Chemical', 'icon' => 'cpu'],
                ['id' => 'business', 'name' => 'Business, Finance & Analytics', 'desc' => 'BBA, Accounting & Finance, Business Analytics, FinTech', 'icon' => 'bar-chart-3'],
                ['id' => 'medical', 'name' => 'Allied Health & Biosciences', 'desc' => 'Biotechnology, Doctor of Physical Therapy (DPT), Pharmacy', 'icon' => 'heart-pulse'],
                ['id' => 'social_sciences', 'name' => 'Social Sciences & Law', 'desc' => 'Economics, Public Administration, International Relations, LLB', 'icon' => 'scale'],
            ],
            'locations' => collect(app(RegionCatalog::class)->regions())->map(fn ($name, $id) => ['id' => $id, 'name' => $name, 'icon' => 'map-pin'])->values()->all(),
            'entryTests' => [
                ['id' => 'net', 'name' => 'NUST Entry Test (NET)', 'max' => 200, 'typical' => 145],
                ['id' => 'ecat', 'name' => 'Punjab ECAT (UET)', 'max' => 400, 'typical' => 240],
                ['id' => 'nat', 'name' => 'NTS National Aptitude Test (NAT)', 'max' => 100, 'typical' => 75],
                ['id' => 'fast', 'name' => 'FAST-NU Admission Test', 'max' => 100, 'typical' => 68],
                ['id' => 'sat', 'name' => 'SAT / ACT Standardized Test', 'max' => 1600, 'typical' => 1350],
                ['id' => 'none', 'name' => 'Not Taken Yet / Test Exempt', 'max' => 100, 'typical' => 70],
            ],
        ]);
    }

    /**
     * AI Recommendation Results Screen (Reach, Match, Safety tiers + Merit evaluation).
     */
    public function recommendations(Request $request): View
    {
        $profile = [
            'matric_percentage' => floatval($request->input('matric_percentage', 82.5)),
            'fsc_percentage' => floatval($request->input('fsc_percentage', 78.0)),
            'test_type' => $request->input('test_type', 'net'),
            'test_score' => floatval($request->input('test_score', 145)),
            'discipline' => $request->input('discipline', 'computing'),
            'location' => $request->input('location', 'islamabad'),
            'budget_max' => floatval($request->input('budget_max', 200000)),
            'monthly_income' => floatval($request->input('monthly_income', 55000)),
        ];

        $evaluation = $this->counselorService->evaluateStudent($profile);

        return view('pages.counselor.recommendations', [
            'evaluation' => $evaluation,
            'profile' => $evaluation['profile'],
            'tiers' => $evaluation['tiers'],
            'aggregates' => $evaluation['calculated_aggregates'],
            'scholarships' => $evaluation['scholarships'],
            'summaryVerdict' => $evaluation['summary_verdict'],
        ]);
    }

    /**
     * Conversational Natural Language University & Program Search.
     */
    public function search(Request $request): View
    {
        $query = $request->input('q', 'Top CS universities in Islamabad under 200k with hostel');
        $searchResult = $this->counselorService->searchConversational($query);

        return view('pages.counselor.search', [
            'query' => $query,
            'result' => $searchResult,
            'criteria' => $searchResult['interpreted_criteria'],
            'matches' => $searchResult['matches'],
            'sampleQueries' => [
                'Top CS universities in Islamabad under 200k with hostel',
                'Best engineering colleges in Lahore with low tuition fees',
                'Which universities offer HEC need-based scholarships in Punjab?',
                'Compare FAST vs NUST for Artificial Intelligence',
                'BBA degrees in Karachi under 150k per semester',
            ],
        ]);
    }

    /**
     * AI Source and Evidence Viewer (Statutory Acts, Gazette Clauses, Accreditation Audits).
     */
    public function evidence(?string $id = null): View
    {
        abort_if($id !== null && ! ctype_digit($id), 404);
        $query = UniversitySourceRecord::with(['university', 'dataSource'])->whereHas('dataSource', fn ($source) => $source->whereNotNull('source_url'));
        if ($id !== null) {
            $query->whereKey($id);
            abort_unless((clone $query)->exists(), 404);
        }

        return view('pages.counselor.evidence', ['records' => $query->latest('id')->paginate(20)]);
    }

    /**
     * AI Chat History & Archive.
     */
    public function history(Request $request): View
    {
        $validated = $request->validate(['q' => 'nullable|string|max:120', 'filter' => 'nullable|in:all,admissions,comparisons,scholarships']);
        $search = $validated['q'] ?? '';

        return view('pages.counselor.chat', ['activeConversation' => null, 'historySearch' => $search,
            'conversations' => app(CounselorWorkspace::class)->history($search, $validated['filter'] ?? 'all')]);
    }

    /**
     * Delete a conversation from history.
     */
    public function destroy(Request $request, string $id): JsonResponse|RedirectResponse
    {
        abort_unless($request->user(), 401);
        $conversation = app(CounselorWorkspace::class)->owned($id, 'delete');
        $conversation->delete();
        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('counselor.history')->with('toast_success', 'Conversation removed from history.')->with('status', 'Conversation removed from history.');
    }
}
