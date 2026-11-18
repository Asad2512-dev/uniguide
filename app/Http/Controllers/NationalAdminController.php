<?php

namespace App\Http\Controllers;

use App\Services\NationalAdminService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NationalAdminController extends Controller
{
    public function __construct(
        protected NationalAdminService $nationalService
    ) {}

    /**
     * 1. Overview Dashboard
     */
    public function dashboard(): View
    {
        $data = $this->nationalService->getDashboardData();

        return view('pages.national-admin.dashboard', $data);
    }

    /**
     * 2. University Registry
     */
    public function universities(): View
    {
        $universities = $this->nationalService->getUniversities();

        return view('pages.national-admin.universities', [
            'universities' => $universities,
        ]);
    }

    /**
     * 3. University Approval Center
     */
    public function approvals(): RedirectResponse
    {
        return redirect()->route('national_admin.applications');
    }

    public function processApproval(Request $request, string $id): RedirectResponse
    {
        return redirect()->route('national_admin.applications');
    }

    /**
     * 4. Campus and Program Registry
     */
    public function programs(): View
    {
        $data = $this->nationalService->getProgramsAndCampuses();

        return view('pages.national-admin.programs', $data);
    }

    /**
     * 5. Official Data Source Management
     */
    public function sources(): View
    {
        $sources = $this->nationalService->getDataSources();

        return view('pages.national-admin.sources', [
            'sources' => $sources,
        ]);
    }

    public function createSource(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'institution' => 'required|string|max:200',
            'base_url' => 'required|url',
        ]);

        $this->nationalService->createDataSource($request->all());

        return back()->with('toast_success', 'Official ingestion data source registered successfully.');
    }

    /**
     * 6. Web Scraping Control Center
     */
    public function scraping(): View
    {
        $data = $this->nationalService->getScrapingControl();

        return view('pages.national-admin.scraping', $data);
    }

    public function triggerCrawl(Request $request): RedirectResponse
    {
        $source = $request->input('source_name', 'All Active Institutional Repositories');
        $this->nationalService->triggerCrawl($source);

        return back()->with('toast_success', 'Collection review dispatched for '.htmlspecialchars($source).'.');
    }

    public function retryFailedCrawls(): RedirectResponse
    {
        $this->nationalService->retryFailedCrawls();

        return back()->with('toast_success', 'Re-queued all failed crawler jobs with exponential backoff.');
    }

    /**
     * 7. Scraping Job History
     */
    public function scrapingHistory(): View
    {
        $history = $this->nationalService->getScrapingHistory();

        return view('pages.national-admin.scraping-history', [
            'history' => $history,
        ]);
    }

    /**
     * 8. AI Extraction Review Workspace
     */
    public function aiExtraction(): View
    {
        $items = $this->nationalService->getAiExtractions();

        return view('pages.national-admin.ai-extraction', [
            'extractions' => $items,
        ]);
    }

    public function processExtraction(Request $request, string $id): RedirectResponse
    {
        $action = $request->input('action', 'approved');
        $editedValue = $request->input('edited_value');
        $this->nationalService->processExtraction($id, $action, $editedValue);

        return back()->with('toast_success', 'Extraction decision recorded: '.ucfirst($action).'.');
    }

    /**
     * 9. Data Verification Queue
     */
    public function verification(): View
    {
        $queue = $this->nationalService->getVerificationQueue();

        return view('pages.national-admin.verification', [
            'queue' => $queue,
        ]);
    }

    public function processVerification(Request $request, string $id): RedirectResponse
    {
        $action = $request->input('action', 'approved');
        $this->nationalService->processVerification($id, $action);

        return back()->with('toast_success', 'Verification queue item status updated to '.ucfirst($action).'.');
    }

    /**
     * 10. Knowledge Base Management (Vector Store)
     */
    public function knowledgeBase(): View
    {
        $data = $this->nationalService->getKnowledgeBase();

        return view('pages.national-admin.knowledge-base', $data);
    }

    public function reindexKnowledgeBase(): RedirectResponse
    {
        $this->nationalService->reindexKnowledgeBase();

        return back()->with('toast_success', 'Indexing request processed.');
    }

    /**
     * 11. AI Chatbot Configuration
     */
    public function chatbotConfig(): View
    {
        $config = $this->nationalService->getChatbotConfig();

        return view('pages.national-admin.chatbot-config', [
            'config' => $config,
        ]);
    }

    public function updateChatbotConfig(Request $request): RedirectResponse
    {
        $request->validate([
            'model' => 'required|string',
            'temperature' => 'required|numeric|min:0|max:1',
            'max_tokens' => 'required|numeric',
            'citation_threshold' => 'required|numeric|min:0|max:1',
            'system_prompt' => 'required|string',
        ]);

        $this->nationalService->updateChatbotConfig([
            'model' => $request->input('model'),
            'temperature' => floatval($request->input('temperature')),
            'max_tokens' => intval($request->input('max_tokens')),
            'citation_threshold' => floatval($request->input('citation_threshold')),
            'system_prompt' => $request->input('system_prompt'),
            'inject_pec_rules' => $request->has('inject_pec_rules'),
            'inject_hec_premed_rules' => $request->has('inject_hec_premed_rules'),
            'inject_ibcc_rules' => $request->has('inject_ibcc_rules'),
            'enforce_strict_citations' => $request->has('enforce_strict_citations'),
            'guardrails_hallucination_block' => $request->has('guardrails_hallucination_block'),
        ]);

        return back()->with('toast_success', 'AI Counselor federal hyperparameters and statutory guardrails saved.');
    }

    /**
     * 12. Student and Admin User Management
     */
    public function users(): View
    {
        $users = $this->nationalService->getUsers();

        return view('pages.national-admin.users', [
            'users' => $users,
        ]);
    }

    public function inviteUser(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email',
            'role' => 'required|string',
            'scope' => 'required|string',
        ]);

        $this->nationalService->inviteUser([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'role' => $request->input('role'),
            'scope' => $request->input('scope'),
            'two_factor' => $request->has('two_factor'),
        ]);

        return back()->with('toast_success', 'Invitation request saved.');
    }

    /**
     * 13. Notifications Management
     */
    public function notifications(): View
    {
        $notifications = $this->nationalService->getNotifications();

        return view('pages.national-admin.notifications', [
            'notifications' => $notifications,
        ]);
    }

    public function createNotification(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'category' => 'required|string',
            'target_audience' => 'required|in:all,universities,students',
            'message' => 'required|string|max:5000',
        ]);

        $this->nationalService->createNotification($request->only(['title', 'category', 'target_audience', 'message']));

        return back()->with('toast_success', 'In-app notifications saved for the selected recipients.');
    }

    /**
     * 14. Analytics and Reports
     */
    public function analytics(): View
    {
        $analytics = $this->nationalService->getAnalytics();

        return view('pages.national-admin.analytics', $analytics);
    }

    /**
     * 15. System Settings and Audit Logs
     */
    public function settings(): View
    {
        $data = $this->nationalService->getSettingsAndAuditLogs();

        return view('pages.national-admin.settings', $data);
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $this->nationalService->updateSettings([
            'crawler_concurrency' => intval($request->input('crawler_concurrency', 8)),
            'api_rate_limit_per_minute' => intval($request->input('api_rate_limit_per_minute', 600)),
            'require_hec_commissioner_approval_for_fee_hike' => $request->has('require_hec_commissioner_approval_for_fee_hike'),
            'immutable_audit_logging_enabled' => $request->has('immutable_audit_logging_enabled'),
        ]);

        return back()->with('toast_success', 'platform system settings updated.');
    }
}
