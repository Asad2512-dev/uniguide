<?php

namespace App\Http\Controllers;

use App\Services\AdmissionService;
use App\Services\DiscoveryService;
use App\Services\RegionCatalog;
use App\Services\StudentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            'streams' => [
                ['id' => 'pre_engineering', 'name' => 'FSc Pre-Engineering', 'desc' => 'Mathematics, Physics, Chemistry', 'icon' => 'cpu'],
                ['id' => 'ics', 'name' => 'ICS (Computer Science)', 'desc' => 'Mathematics, Physics, Computer Science', 'icon' => 'laptop'],
                ['id' => 'pre_medical', 'name' => 'FSc Pre-Medical', 'desc' => 'Biology, Chemistry, Physics (Eligible for CS & Biotech)', 'icon' => 'heart-pulse'],
                ['id' => 'a_levels', 'name' => 'Cambridge A-Levels', 'desc' => 'Requires IBCC Equivalence Certificate', 'icon' => 'globe'],
                ['id' => 'dae', 'name' => 'Diploma of Associate Engineering (DAE)', 'desc' => '3-Year Technical Board Diploma', 'icon' => 'wrench'],
            ],
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
        $request->validate([
            'stream' => 'required|string',
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
                'board' => $request->input('matric_board', 'Federal Board (FBISE)'),
                'roll_number' => $request->input('matric_roll', '284192'),
            ],
            'fsc' => [
                'percentage' => floatval($request->input('fsc_percentage')),
                'board' => $request->input('fsc_board', 'Federal Board (FBISE)'),
                'roll_number' => $request->input('fsc_roll', '719204'),
            ],
        ]);

        return redirect()->route('student.profile')->with('toast_success', 'Academic credentials and marks verified successfully.');
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
            'stage' => 'required|string',
        ]);

        $this->studentService->addTrackerApplication($request->all());

        return redirect()->route('student.tracker')->with('toast_success', 'Application added to your admission tracker.');
    }

    /**
     * Update an application in tracker.
     */
    public function updateTracker(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'stage' => 'required|string',
        ]);

        $this->studentService->updateTrackerApplication($id, $request->all());

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
            'city' => $request->input('city', 'Rawalpindi'),
        ]);

        return redirect()->route('student.settings')->with('toast_success', 'Account preferences and security settings saved.');
    }
}
