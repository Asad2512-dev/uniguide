<?php

namespace App\Http\Controllers;

use App\Services\UniversityAdminService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UniversityAdminController extends Controller
{
    public function __construct(
        protected UniversityAdminService $adminService
    ) {}

    /**
     * 1. University Dashboard
     */
    public function dashboard(): View
    {
        $data = $this->adminService->getDashboardData();

        return view('pages.uni-admin.dashboard', $data);
    }

    /**
     * 2. University Profile Management
     */
    public function profile(): View
    {
        $profile = $this->adminService->getProfile();

        return view('pages.uni-admin.profile', [
            'profile' => $profile,
            'institution' => $this->adminService->getInstitution(),
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'helpline' => 'required|string|max:50',
            'admissions_email' => 'required|email|max:100',
            'website' => 'required|url',
            'address' => 'required|string',
        ]);

        $this->adminService->updateProfile($request->only([
            'name', 'helpline', 'admissions_email', 'website', 'address', 'chancellor', 'rector',
        ]));

        return back()->with('toast_success', 'Institutional profile successfully updated.');
    }

    /**
     * 3. Campus Management
     */
    public function campuses(): View
    {
        $campuses = $this->adminService->getCampuses();

        return view('pages.uni-admin.campuses', [
            'campuses' => $campuses,
            'institution' => $this->adminService->getInstitution(),
        ]);
    }

    public function storeCampus(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'land_area' => 'required|string|max:50',
            'student_capacity' => 'required|numeric',
            'hostel_capacity' => 'required|string',
            'head' => 'required|string',
        ]);

        $this->adminService->storeCampus($request->only([
            'name', 'city', 'province', 'land_area', 'student_capacity', 'hostel_capacity', 'head', 'address',
        ]));

        return back()->with('toast_success', 'New university campus added successfully.');
    }

    public function updateCampus(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'city' => 'required|string|max:100',
            'student_capacity' => 'required|numeric',
        ]);

        $this->adminService->updateCampus($id, $request->only([
            'name', 'city', 'province', 'land_area', 'student_capacity', 'hostel_capacity', 'head',
        ]));

        return back()->with('toast_success', 'Campus details updated.');
    }

    /**
     * 4. Department Management
     */
    public function departments(): View
    {
        $departments = $this->adminService->getDepartments();
        $campuses = $this->adminService->getCampuses();

        return view('pages.uni-admin.departments', [
            'departments' => $departments,
            'campuses' => $campuses,
            'institution' => $this->adminService->getInstitution(),
        ]);
    }

    public function storeDepartment(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'acronym' => 'required|string|max:20',
            'head' => 'required|string|max:100',
            'head_title' => 'required|string|max:50',
            'campus' => 'required|string',
            'faculty_count' => 'required|numeric',
            'phd_faculty_ratio' => 'required|string',
        ]);

        $this->adminService->storeDepartment($request->only([
            'name', 'acronym', 'head', 'head_title', 'campus', 'faculty_count', 'phd_faculty_ratio',
        ]));

        return back()->with('toast_success', 'Academic department created successfully.');
    }

    /**
     * 5. Program Management
     */
    public function programs(): View
    {
        $programs = $this->adminService->getPrograms();
        $departments = $this->adminService->getDepartments();

        return view('pages.uni-admin.programs', [
            'programs' => $programs,
            'departments' => $departments,
            'institution' => $this->adminService->getInstitution(),
        ]);
    }

    public function storeProgram(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'code' => 'required|string|max:50',
            'level' => 'required|string',
            'department' => 'required|string',
            'duration' => 'required|string',
            'credit_hours' => 'required|numeric',
            'accreditation' => 'required|string',
            'intake_seats' => 'required|numeric',
            'tuition_semester' => 'required|string',
        ]);

        $this->adminService->storeProgram($request->only([
            'name', 'code', 'level', 'department', 'campus', 'duration', 'credit_hours',
            'study_mode', 'accreditation', 'intake_seats', 'tuition_semester', 'min_eligibility', 'merit_formula',
        ]));

        return back()->with('toast_success', 'Academic degree program published to national registry.');
    }

    public function updateProgram(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'intake_seats' => 'required|numeric',
            'tuition_semester' => 'required|string',
        ]);

        $this->adminService->updateProgram($id, $request->only([
            'name', 'intake_seats', 'tuition_semester', 'duration', 'credit_hours', 'accreditation', 'status',
        ]));

        return back()->with('toast_success', 'Program details updated successfully.');
    }

    /**
     * 6. Fee Management
     */
    public function fees(): View
    {
        $fees = $this->adminService->getFeeSchedules();

        return view('pages.uni-admin.fees', [
            'fees' => $fees,
            'institution' => $this->adminService->getInstitution(),
        ]);
    }

    public function updateFees(Request $request): RedirectResponse
    {
        $request->validate([
            'category_index' => 'required|numeric',
            'tuition' => 'required|numeric|min:10000',
        ]);

        $this->adminService->updateFees($request->only(['category_index', 'tuition']));

        return back()->with('toast_success', 'Tuition fee schedule updated.');
    }

    /**
     * 7. Admission Management
     */
    public function admissions(): View
    {
        $cycles = $this->adminService->getAdmissionCycles();

        return view('pages.uni-admin.admissions', [
            'cycles' => $cycles,
            'institution' => $this->adminService->getInstitution(),
        ]);
    }

    public function updateAdmissions(Request $request): RedirectResponse
    {
        $request->validate([
            'cycle_id' => 'required|string',
            'deadline_date' => 'required|string',
            'status' => 'required|string',
        ]);

        $this->adminService->updateAdmissionCycle($request->input('cycle_id'), $request->only([
            'deadline_date', 'status', 'entry_tests', 'merit_list_1', 'merit_list_2',
        ]));

        return back()->with('toast_success', 'Admission cycle timeline updated.');
    }

    /**
     * 8. Eligibility Rules Management
     */
    public function eligibility(): View
    {
        $eligibility = $this->adminService->getEligibilityRules();

        return view('pages.uni-admin.eligibility', [
            'eligibility' => $eligibility,
            'institution' => $this->adminService->getInstitution(),
        ]);
    }

    public function updateEligibility(Request $request): RedirectResponse
    {
        $request->validate([
            'net_weight' => 'required|numeric|min:0|max:100',
            'fsc_weight' => 'required|numeric|min:0|max:100',
            'matric_weight' => 'required|numeric|min:0|max:100',
        ]);

        $total = $request->input('net_weight') + $request->input('fsc_weight') + $request->input('matric_weight');
        if ($total !== 100) {
            return back()->withErrors(['net_weight' => 'The sum of weights must equal 100% (currently '.$total.'%).']);
        }

        $this->adminService->updateEligibilityRules($request->only(['net_weight', 'fsc_weight', 'matric_weight']));

        return back()->with('toast_success', 'Merit weightage formula updated successfully.');
    }

    /**
     * 9. Scholarship Management
     */
    public function scholarships(): View
    {
        $scholarships = $this->adminService->getScholarships();

        return view('pages.uni-admin.scholarships', [
            'scholarships' => $scholarships,
            'institution' => $this->adminService->getInstitution(),
        ]);
    }

    public function storeScholarship(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'funding_agency' => 'required|string|max:150',
            'category' => 'required|string',
            'coverage' => 'required|string',
            'annual_budget' => 'required|string',
            'beneficiaries_target' => 'required|numeric',
            'eligibility_criteria' => 'required|string',
        ]);

        $this->adminService->storeScholarship($request->only([
            'name', 'funding_agency', 'category', 'coverage', 'annual_budget', 'beneficiaries_target', 'eligibility_criteria', 'application_window',
        ]));

        return back()->with('toast_success', 'Financial aid scheme registered.');
    }

    /**
     * 10. Document Management
     */
    public function documents(): View
    {
        $documents = $this->adminService->getDocuments();

        return view('pages.uni-admin.documents', [
            'documents' => $documents,
            'institution' => $this->adminService->getInstitution(),
        ]);
    }

    public function verifyDocument(Request $request, string $id): RedirectResponse
    {
        $status = $request->input('status', 'verified');
        $this->adminService->verifyDocument($id, $status);

        return back()->with('toast_success', 'Candidate document status updated to '.ucfirst($status).'.');
    }

    public function uploadDocument(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'type' => 'required|string',
        ]);

        return back()->with('toast_success', 'Official institutional document uploaded and queued for HEC validation.');
    }

    /**
     * 11. AI Data Verification (Flagship Review Experience)
     */
    public function aiVerification(): View
    {
        $queue = $this->adminService->getAiVerificationQueue();

        return view('pages.uni-admin.ai-verification', [
            'queue' => $queue,
            'institution' => $this->adminService->getInstitution(),
        ]);
    }

    public function approveAiVerification(string $id): RedirectResponse
    {
        $this->adminService->processAiVerification($id, 'approved');

        return back()->with('toast_success', 'AI-extracted field verified and applied to live institutional record.');
    }

    public function rejectAiVerification(string $id): RedirectResponse
    {
        $this->adminService->processAiVerification($id, 'rejected');

        return back()->with('toast_success', 'AI proposed modification rejected. Previous official value preserved.');
    }

    public function editAiVerification(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'edited_value' => 'required|string',
        ]);

        $this->adminService->processAiVerification($id, 'edited', $request->input('edited_value'));

        return back()->with('toast_success', 'Modified value approved and published to live registry.');
    }

    /**
     * 12. Student Inquiry Management
     */
    public function inquiries(): View
    {
        $inquiries = $this->adminService->getInquiries();

        return view('pages.uni-admin.inquiries', [
            'inquiries' => $inquiries,
            'institution' => $this->adminService->getInstitution(),
        ]);
    }

    public function replyInquiry(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'reply' => 'required|string|min:5',
        ]);

        $this->adminService->replyInquiry($id, $request->input('reply'));

        return back()->with('toast_success', 'Official response dispatched to applicant via email and student portal.');
    }

    public function updateInquiryStatus(Request $request, string $id): RedirectResponse
    {
        $status = $request->input('status', 'in_progress');
        $this->adminService->updateInquiryStatus($id, $status);

        return back()->with('toast_success', 'Inquiry triage status updated.');
    }

    /**
     * 13. Staff and Settings
     */
    public function settings(): View
    {
        $data = $this->adminService->getStaffAndSettings();

        return view('pages.uni-admin.settings', [
            'staff' => $data['staff'],
            'settings' => $data['settings'],
            'audit_log' => $data['audit_log'],
            'institution' => $this->adminService->getInstitution(),
        ]);
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $this->adminService->updateSettings([
            'two_factor_required' => $request->has('two_factor_required'),
            'auto_ai_scrape_notification' => $request->has('auto_ai_scrape_notification'),
            'weekly_inquiry_digest' => $request->has('weekly_inquiry_digest'),
            'notify_email' => $request->input('notify_email', 'admissions.alerts@nust.edu.pk'),
        ]);

        return back()->with('toast_success', 'Security protocols and institutional notification settings saved.');
    }
}
