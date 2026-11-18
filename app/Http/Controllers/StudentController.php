<?php

namespace App\Http\Controllers;

use App\Services\AdmissionService;
use App\Services\DiscoveryService;
use App\Services\RegionCatalog;
use App\Services\StudentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function __construct(
        protected StudentService $studentService,
        protected DiscoveryService $discoveryService,
        protected AdmissionService $admissionService
    ) {}

    /**
     * 1. Student Onboarding: Multi-step wizard collecting qualifications, preferences, and goals.
     */
    public function onboarding(): View
    {
        $profile = $this->studentService->getProfile();

        return view('pages.student.onboarding', [
            'profile' => $profile,
            'streams' => collect(config('academic_streams.options'))->map(fn ($option, $id) => ['id' => $id] + $option)->values()->all(),
            'cities' => array_values(app(RegionCatalog::class)->cities()),
            'disciplines' => [
                'Computer Science & AI', 'Software Engineering', 'Data Science & Cyber Security', 'Electrical Engineering',
                'Mechanical & Mechatronics', 'Business & Management (BBA)', 'Allied Health & Biosciences',
            ],
        ]);
    }

    /**
     * Process onboarding submission.
     */
    public function processOnboarding(Request $request): RedirectResponse
    {
        if (is_string($request->input('stream'))) {
            $request->merge(['stream' => config('academic_streams.aliases.'.$request->input('stream'), $request->input('stream'))]);
        }
        $request->validate([
            'stream' => ['required', 'string', Rule::in(array_keys(config('academic_streams.options')))],
            'matric_percentage' => 'required|numeric|min:50|max:100',
            'fsc_percentage' => 'required|numeric|min:50|max:100',
            'target_cities' => 'required|array|min:1',
            'target_cities.*' => 'required|string',
            'target_disciplines' => 'required|array|min:1',
            'budget_max' => 'required|numeric',
        ]);

        $this->studentService->updateProfile([
            'stream' => $request->input('stream'),
            'onboarding_completed' => true,
            'matric' => [
                'percentage' => floatval($request->input('matric_percentage')),
                'obtained_marks' => round((floatval($request->input('matric_percentage')) / 100) * 1100),
            ],
            'fsc' => [
                'percentage' => floatval($request->input('fsc_percentage')),
                'obtained_marks' => round((floatval($request->input('fsc_percentage')) / 100) * 520),
            ],
            'preferences' => [
                'target_cities' => $request->input('target_cities'),
                'target_disciplines' => $request->input('target_disciplines'),
                'budget_max' => floatval($request->input('budget_max')),
                'scholarship_interest' => $request->input('scholarship_interest', 'need_and_merit'),
            ],
        ]);

        return redirect()->route('student.dashboard')->with('toast_success', 'Onboarding completed! Your personalized admission command center is ready.');
    }

    /**
     * 2. Student Dashboard: Personalized command center.
     */
    public function dashboard(): View
    {
        $profile = $this->studentService->getProfile();
        $completeness = $this->studentService->getCompleteness();
        $applications = $this->studentService->getTrackerApplications();
        $savedUnis = $this->studentService->getSavedUniversities();
        $savedScholarships = $this->studentService->getSavedScholarships();
        $recommendations = $this->studentService->getPersonalizedRecommendations();
        $notifications = $this->studentService->getNotifications();

        // Calculate upcoming deadlines across tracker and saved items
        $upcomingDeadlines = array_slice($applications, 0, 3);

        return view('pages.student.dashboard', [
            'profile' => $profile,
            'completeness' => $completeness,
            'applications' => $applications,
            'savedUnis' => $savedUnis,
            'savedScholarships' => $savedScholarships,
            'recommendations' => $recommendations,
            'notifications' => array_slice($notifications, 0, 3),
            'upcomingDeadlines' => $upcomingDeadlines,
        ]);
    }

    /**
     * 3. Academic Profile: Comprehensive educational credentials.
     */
    public function profile(): View
    {
        $profile = $this->studentService->getProfile();
        $completeness = $this->studentService->getCompleteness();

        return view('pages.student.profile', [
            'profile' => $profile,
            'completeness' => $completeness,
        ]);
    }

    /**
     * Update student academic profile.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $request->validate([
            'full_name' => 'required|string|max:120',
            'phone' => 'required|string|max:30',
            'city' => 'required|string|max:60',
            'matric_percentage' => 'required|numeric|min:50|max:100',
            'fsc_percentage' => 'required|numeric|min:50|max:100',
        ]);

        $this->studentService->updateProfile([
            'full_name' => $request->input('full_name'),
            'phone' => $request->input('phone'),
            'city' => $request->input('city'),
            'matric' => [
                'percentage' => floatval($request->input('matric_percentage')),
                'board' => $request->input('matric_board', ''),
                'roll_number' => $request->input('matric_roll', ''),
            ],
            'fsc' => [
                'percentage' => floatval($request->input('fsc_percentage')),
                'board' => $request->input('fsc_board', ''),
                'roll_number' => $request->input('fsc_roll', ''),
            ],
        ]);

        return redirect()->route('student.profile')->with('toast_success', 'Academic profile saved. Entered results have not been independently verified.');
    }

    /**
     * 4. Personalized Recommendations: Customized Reach, Match, Safety tiers.
     */
    public function recommendations(): View
    {
        $profile = $this->studentService->getProfile();
        $recommendations = $this->studentService->getPersonalizedRecommendations();

        return view('pages.student.recommendations', [
            'profile' => $profile,
            'evaluation' => $recommendations,
            'tiers' => $recommendations['tiers'],
            'aggregates' => $recommendations['calculated_aggregates'],
            'scholarships' => $recommendations['scholarships'],
            'summaryVerdict' => $recommendations['summary_verdict'],
        ]);
    }

    /**
     * 5. Saved Universities collection.
     */
    public function savedUniversities(Request $request): View
    {
        $sort = $request->input('sort', 'rank');
        $saved = $this->studentService->getSavedUniversities($sort);

        return view('pages.student.saved-universities', [
            'universities' => $saved,
            'activeSort' => $sort,
        ]);
    }

    /**
     * Remove university from saved collection.
     */
    public function removeSavedUniversity(string $slug): RedirectResponse
    {
        $this->studentService->removeSavedUniversity($slug);

        return redirect()->route('student.saved.universities')->with('toast_success', 'University removed from your shortlisted collection.');
    }

    /**
     * 6. Saved Programs collection.
     */
    public function savedPrograms(): View
    {
        $programs = $this->studentService->getSavedPrograms();

        return view('pages.student.saved-programs', [
            'programs' => $programs,
        ]);
    }

    /**
     * Remove program from saved collection.
     */
    public function removeSavedProgram(string $slug): RedirectResponse
    {
        $this->studentService->removeSavedProgram($slug);

        return redirect()->route('student.saved.programs')->with('toast_success', 'Degree program removed from saved collection.');
    }

    /**
     * 7. Saved Scholarships collection.
     */
    public function savedScholarships(): View
    {
        $profile = $this->studentService->getProfile();
        $scholarships = $this->studentService->getSavedScholarships();

        return view('pages.student.saved-scholarships', [
            'scholarships' => $scholarships,
            'profile' => $profile,
        ]);
    }

    /**
     * Remove scholarship from saved collection.
     */
    public function removeSavedScholarship(string $slug): RedirectResponse
    {
        $this->studentService->removeSavedScholarship($slug);

        return redirect()->route('student.saved.scholarships')->with('toast_success', 'Scholarship removed from your saved list.');
    }

    /**
     * 8. My Admission Tracker: Application lifecycle management.
     */
    public function tracker(): View
    {
        $applications = $this->studentService->getTrackerApplications();

        return view('pages.student.tracker', [
            'applications' => $applications,
            'stages' => [
                'interested' => 'Interested',
                'preparing' => 'Preparing',
                'applied' => 'Applied',
                'test_scheduled' => 'Test Scheduled',
                'awaiting_result' => 'Awaiting Result',
                'offer_received' => 'Offer Received',
                'closed' => 'Closed',
            ],
        ]);
    }

    /**
     * Store a new application in tracker.
     */
    public function storeTracker(Request $request): RedirectResponse
    {
        $request->validate([
            'program_id' => 'required|integer',
            'stage' => 'required|in:interested,preparing,applied,test_scheduled,awaiting_result,offer_received,closed',
        ]);

        $this->studentService->addTrackerApplication($request->only(['program_id', 'stage', 'notes', 'application_ref', 'test_date', 'fee_challan_status']));

        return redirect()->route('student.tracker')->with('toast_success', 'Application added to your admission tracker.');
    }

    /**
     * Update an application in tracker.
     */
    public function updateTracker(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'stage' => 'required|in:interested,preparing,applied,test_scheduled,awaiting_result,offer_received,closed',
        ]);

        $this->studentService->updateTrackerApplication($id, $request->only(['program_id', 'stage', 'notes', 'application_ref', 'test_date', 'fee_challan_status']));

        return redirect()->route('student.tracker')->with('toast_success', 'Application status updated successfully.');
    }

    /**
     * Remove an application from tracker.
     */
    public function deleteTracker(string $id): RedirectResponse
    {
        $this->studentService->deleteTrackerApplication($id);

        return redirect()->route('student.tracker')->with('toast_success', 'Application removed from tracker.');
    }

    /**
     * 9. Notifications Center.
     */
    public function notifications(Request $request): View
    {
        $filter = $request->input('filter', 'all');
        $notifications = $this->studentService->getNotifications($filter);

        return view('pages.student.notifications', [
            'notifications' => $notifications,
            'activeFilter' => $filter,
            'filters' => [
                'all' => 'All Alerts',
                'deadline' => 'Deadlines',
                'merit' => 'Merit & Tests',
                'scholarship' => 'Scholarships',
                'system' => 'System Updates',
            ],
        ]);
    }

    /**
     * Mark notification as read.
     */
    public function markNotificationRead(string $id): RedirectResponse
    {
        $this->studentService->markNotificationRead($id);

        return redirect()->route('student.notifications')->with('toast_success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllNotificationsRead(): RedirectResponse
    {
        $this->studentService->markAllNotificationsRead();

        return redirect()->route('student.notifications')->with('toast_success', 'All notifications marked as read.');
    }

    /**
     * 10. Account Settings: Profile, notifications, security, privacy.
     */
    public function settings(): View
    {
        $profile = $this->studentService->getProfile();

        return view('pages.student.settings', [
            'profile' => $profile,
        ]);
    }

    /**
     * Update account settings.
     */
    public function updateSettings(Request $request): RedirectResponse
    {
        $request->validate([
            'full_name' => 'required|string|max:120',
            'phone' => 'required|string|max:30',
        ]);

        $this->studentService->updateProfile([
            'full_name' => $request->input('full_name'),
            'phone' => $request->input('phone'),
            'city' => $request->input('city', ''),
            'preferences' => ['deadline_reminders' => $request->boolean('deadline_reminders'), 'merit_updates' => $request->boolean('merit_updates'), 'scholarship_updates' => $request->boolean('scholarship_updates')],
        ]);

        return redirect()->route('student.settings')->with('toast_success', 'Account preferences and security settings saved.');
    }

    public function exportData(Request $request)
    {
        $user = $request->user();

        return response()->streamDownload(function () use ($user) {
            echo json_encode(['account' => $user->only(['name', 'email', 'phone', 'province']), 'profile' => $user->profile?->toArray(), 'applications' => $user->applications()->get()->toArray(), 'saved_items' => $user->savedItems()->get()->toArray()], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }, 'uniguide-account.json', ['Content-Type' => 'application/json']);
    }

    public function uploadDocument(Request $request)
    {
        $data = $request->validate(['title' => 'required|string|max:200', 'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240']);
        $this->studentService->updateProfile([]);
        $profile = $request->user()->profile()->firstOrFail();
        $file = $request->file('document');
        $path = $file->store('student-documents/'.$request->user()->id, 'local');
        $documents = $profile->documents_data ?? [];
        $documents[] = ['id' => (string) Str::uuid(), 'title' => $data['title'], 'file_name' => $file->getClientOriginalName(), 'path' => $path, 'status' => 'pending', 'status_label' => 'Pending review', 'uploaded_at' => now()->toDateTimeString()];
        $profile->update(['documents_data' => $documents]);

        return back()->with('toast_success', 'Document uploaded privately.');
    }

    public function downloadDocument(Request $request, string $id)
    {
        $document = collect($request->user()->profile()->first()?->documents_data ?? [])->firstWhere('id', $id);
        abort_unless($document, 404);

        return Storage::disk('local')->download($document['path'], $document['file_name']);
    }

    public function storeEntryTest(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:150', 'test_type' => 'required|string|max:50', 'score' => 'required|numeric|min:0', 'max_score' => 'required|numeric|min:1', 'test_date' => 'nullable|date']);
        if ($data['score'] > $data['max_score']) {
            return back()->withErrors(['score' => 'Score cannot exceed the maximum.']);
        }
        $profile = $this->studentService->getProfile();
        $tests = $profile['entry_tests'];
        $tests[] = $data + ['id' => (string) Str::uuid(), 'percentage' => round($data['score'] / $data['max_score'] * 100, 2), 'status' => 'Self-reported', 'roll_number' => ''];
        $this->studentService->updateProfile(['entry_tests' => $tests]);

        return back()->with('toast_success', 'Entry test result saved as self-reported.');
    }
}
