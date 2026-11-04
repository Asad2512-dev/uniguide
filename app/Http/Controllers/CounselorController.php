<?php

namespace App\Http\Controllers;

use App\Services\CounselorService;
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
        return view('pages.counselor.index', [
            'suggestedCategories' => $this->counselorService->getSuggestedPrompts(),
            'quickActions' => $this->counselorService->getQuickActions(),
            'capabilities' => $this->counselorService->getCapabilitiesDisclosure(),
            'recentConversations' => array_slice($this->counselorService->getConversationHistory(), 0, 4),
        ]);
    }

    /**
     * AI Conversation Workspace: Chat interface with streaming simulation, message bubbles, cards.
     */
    public function chat(?string $id = null): View
    {
        $allConversations = $this->counselorService->getConversationHistory();
        $activeConversation = null;

        if ($id) {
            $activeConversation = $this->counselorService->getConversationById($id);
        }

        if (! $activeConversation && count($allConversations) > 0) {
            $activeConversation = $allConversations[0];
        }

        // If still empty, provide default initial conversation
        if (! $activeConversation) {
            $activeConversation = [
                'id' => 'conv-new',
                'title' => 'New Counseling Session',
                'topic' => 'admissions',
                'topic_label' => 'Admissions & Merit',
                'created_at' => now()->format('Y-m-d H:i'),
                'messages' => [],
            ];
        }

        return view('pages.counselor.chat', [
            'activeConversation' => $activeConversation,
            'conversations' => $allConversations,
            'suggestedPrompts' => [
                'What is the difference between NET-1, 2, 3 and 4?',
                'Compare FAST vs NUST Software Engineering',
                'What are the HEC Need-Based scholarship requirements?',
                'Which public universities in Punjab offer BS Data Science?',
            ],
        ]);
    }

    /**
     * Send message endpoint for conversation workspace (supports both AJAX and form submission).
     */
    public function sendMessage(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'conversation_id' => 'nullable|string',
        ]);

        $message = $request->input('message');
        $conversationId = $request->input('conversation_id');

        if ($conversationId && $conversationId !== 'conv-new') {
            $result = $this->counselorService->sendMessage($conversationId, $message);
        } else {
            $conversation = $this->counselorService->createConversation($message);
            $result = [
                'conversation' => $conversation,
                'ai_message' => end($conversation['messages']),
            ];
        }

        if ($request->wantsJson() || $request->ajax()) {
            $userMessages = array_values(array_filter($result['conversation']['messages'], fn ($m) => $m['role'] === 'user'));
            $lastUserMessage = count($userMessages) > 0 ? $userMessages[count($userMessages) - 1] : null;

            return response()->json([
                'success' => true,
                'conversation_id' => $result['conversation']['id'],
                'conversation_title' => $result['conversation']['title'],
                'user_message' => $lastUserMessage,
                'ai_message' => $result['ai_message'],
            ]);
        }

        return redirect()->route('counselor.chat', $result['conversation']['id']);
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
            'locations' => [
                ['id' => 'all', 'name' => 'All Pakistan (Open to Relocation)', 'icon' => 'globe'],
                ['id' => 'islamabad', 'name' => 'Islamabad & Rawalpindi (ICT)', 'icon' => 'building-2'],
                ['id' => 'punjab', 'name' => 'Punjab (Lahore, Faisalabad, Taxila)', 'icon' => 'map-pin'],
                ['id' => 'sindh', 'name' => 'Sindh (Karachi, Jamshoro, Sukkur)', 'icon' => 'map-pin'],
                ['id' => 'kpk', 'name' => 'KPK (Peshawar, Topi, Abbottabad)', 'icon' => 'mountain'],
            ],
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
        $repository = $this->counselorService->getEvidenceRepository();
        $selectedEvidence = null;

        if ($id) {
            $selectedEvidence = $this->counselorService->getEvidenceById($id);
        }

        if (! $selectedEvidence && count($repository) > 0) {
            $selectedEvidence = $repository[0];
        }

        return view('pages.counselor.evidence', [
            'evidenceList' => $repository,
            'selectedEvidence' => $selectedEvidence,
        ]);
    }

    /**
     * AI Chat History & Archive.
     */
    public function history(Request $request): View
    {
        $filter = $request->input('filter', 'all');
        $search = $request->input('q', '');

        $conversations = $this->counselorService->getConversationHistory($filter, $search);

        return view('pages.counselor.history', [
            'conversations' => $conversations,
            'activeFilter' => $filter,
            'searchTerm' => $search,
            'filters' => [
                'all' => 'All Topics',
                'admissions' => 'Admissions & Merit',
                'comparisons' => 'University Comparisons',
                'scholarships' => 'Financial Aid & Scholarships',
            ],
        ]);
    }

    /**
     * Delete a conversation from history.
     */
    public function destroy(string $id): RedirectResponse
    {
        $sessionConversations = session()->get('uniguide_counselor_conversations', []);
        if (isset($sessionConversations[$id])) {
            unset($sessionConversations[$id]);
            session()->put('uniguide_counselor_conversations', $sessionConversations);
        }

        return redirect()->route('counselor.history')->with('status', 'Conversation removed from history.');
    }
}
