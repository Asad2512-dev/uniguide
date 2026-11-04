<?php

namespace Tests\Feature;

use Tests\TestCase;

class UniversityAdminPhaseTest extends TestCase
{
    /**
     * 1. Test University Dashboard renders metrics, sessions, and suggested actions.
     */
    public function test_university_dashboard_renders_metrics_and_actions(): void
    {
        $response = $this->get('/admin/university/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Institutional Command Dashboard');
        $response->assertSee('Institutional Profile Completeness');
        $response->assertSee('Active Programs');
        $response->assertSee('Fall 2026 Applicants');
        $response->assertSee('Recommended Administrative Actions');
        $response->assertSee('Active Admission Sessions');
    }

    /**
     * 2. Test University Profile Management renders and updates institutional records.
     */
    public function test_university_profile_management_renders_and_updates(): void
    {
        $response = $this->get('/admin/university/profile');

        $response->assertStatus(200);
        $response->assertSee('University Profile');
        $response->assertSee('Institutional Identity');
        $response->assertSee('Federal Charter Act', false);
        $response->assertSee('Admissions Contacts');
        $response->assertSee('Digital Portals');
        $response->assertSee('Executive Leadership');
        $response->assertSee('Statutory Accreditations');

        // Update profile
        $updateResponse = $this->post('/admin/university/profile', [
            'name' => 'National University of Sciences & Technology (NUST)',
            'helpline' => '+92 51 111-11-6878',
            'admissions_email' => 'admissions@nust.edu.pk',
            'website' => 'https://nust.edu.pk',
            'address' => 'Sector H-12, Islamabad, 44000, Pakistan',
            'chancellor' => 'President of Pakistan',
            'rector' => 'Lt Gen Javed Mahmood Bukhari HI(M)',
        ]);

        $updateResponse->assertStatus(302);
        $updateResponse->assertSessionHas('toast_success', 'Institutional profile successfully updated.');
    }

    /**
     * 3. Test Campus Management renders campuses and registers new constituent campus.
     */
    public function test_campus_management_renders_and_stores_new_campus(): void
    {
        $response = $this->get('/admin/university/campuses');

        $response->assertStatus(200);
        $response->assertSee('Campus &amp; Infrastructure Management', false);
        $response->assertSee('Main Campus Islamabad');
        $response->assertSee('College of Electrical &amp; Mechanical Engineering', false);
        $response->assertSee('Add Constituent Campus');

        // Store new campus
        $storeResponse = $this->post('/admin/university/campuses', [
            'name' => 'Quetta Constituent Campus',
            'city' => 'Quetta',
            'province' => 'Balochistan',
            'land_area' => '75 Acres',
            'student_capacity' => 2800,
            'hostel_capacity' => '900 beds',
            'head' => 'Brigadier Asif Khan',
            'address' => 'Cantonment Avenue, Quetta',
        ]);

        $storeResponse->assertStatus(302);
        $storeResponse->assertSessionHas('toast_success', 'New university campus added successfully.');
    }

    /**
     * 4. Test Department Management renders departments and registers new department.
     */
    public function test_department_management_renders_and_stores_department(): void
    {
        $response = $this->get('/admin/university/departments');

        $response->assertStatus(200);
        $response->assertSee('Academic Departments &amp; Faculties', false);
        $response->assertSee('School of Electrical Engineering &amp; Computer Science', false);
        $response->assertSee('School of Mechanical &amp; Manufacturing Engineering', false);
        $response->assertSee('Create Department');

        // Store department
        $storeResponse = $this->post('/admin/university/departments', [
            'name' => 'School of Chemical & Materials Engineering',
            'acronym' => 'SCME',
            'head' => 'Dr. Arshad Hussain',
            'head_title' => 'Principal & Dean',
            'campus' => 'Main Campus Islamabad',
            'faculty_count' => 48,
            'phd_faculty_ratio' => '65% PhD Holders',
        ]);

        $storeResponse->assertStatus(302);
        $storeResponse->assertSessionHas('toast_success', 'Academic department created successfully.');
    }

    /**
     * 5. Test Program Management renders catalog and creates new academic program.
     */
    public function test_program_management_renders_and_stores_program(): void
    {
        $response = $this->get('/admin/university/programs');

        $response->assertStatus(200);
        $response->assertSee('Degree Programs &amp; Academic Curricula', false);
        $response->assertSee('BS Computer Science');
        $response->assertSee('BS Software Engineering');
        $response->assertSee('BE Electrical Engineering');
        $response->assertSee('NCEAC Category W');

        // Store program
        $storeResponse = $this->post('/admin/university/programs', [
            'name' => 'BS Cyber Security',
            'code' => 'NUST-SEECS-BSCYS',
            'level' => 'Undergraduate',
            'department' => 'SEECS',
            'campus' => 'Main Campus Islamabad',
            'duration' => '4 Years (8 Semesters)',
            'credit_hours' => 134,
            'study_mode' => 'Full-time On-Campus',
            'accreditation' => 'NCEAC Category W',
            'intake_seats' => 90,
            'tuition_semester' => 'PKR 195,000',
            'min_eligibility' => '50% Intermediate with Math',
            'merit_formula' => '75% NET + 15% FSc + 10% Matric',
        ]);

        $storeResponse->assertStatus(302);
        $storeResponse->assertSessionHas('toast_success', 'Academic degree program published to national registry.');
    }

    /**
     * 6. Test Fee Management renders fee schedules and updates category tuition.
     */
    public function test_fee_management_renders_and_updates_tuition(): void
    {
        $response = $this->get('/admin/university/fees');

        $response->assertStatus(200);
        $response->assertSee('Fee Schedules');
        $response->assertSee('Hostel Tariffs Management');
        $response->assertSee('Undergraduate Computing');
        $response->assertSee('On-Campus Hostels');
        $response->assertSee('Mess Tariffs');
        $response->assertSee('Tri-Seater Room (Standard)');

        // Update fee
        $updateResponse = $this->post('/admin/university/fees', [
            'category_index' => 0,
            'tuition' => 198000,
        ]);

        $updateResponse->assertStatus(302);
        $updateResponse->assertSessionHas('toast_success', 'Tuition fee schedule updated.');
    }

    /**
     * 7. Test Admission Management renders cycles and updates timelines.
     */
    public function test_admission_management_renders_and_updates_cycle(): void
    {
        $response = $this->get('/admin/university/admissions');

        $response->assertStatus(200);
        $response->assertSee('Admission Cycles &amp; Quota Allocations', false);
        $response->assertSee('Fall 2026 Undergraduate Admissions');
        $response->assertSee('NET-4 Series');
        $response->assertSee('Statutory Quota Allocation Scheme');

        // Update cycle
        $updateResponse = $this->post('/admin/university/admissions', [
            'cycle_id' => 'adm-1',
            'status' => 'open',
            'deadline_date' => '20 July 2026',
            'entry_tests' => 'NET-4 Series (Extended Testing Slots)',
            'merit_list_1' => '25 July 2026',
            'merit_list_2' => '02 August 2026',
        ]);

        $updateResponse->assertStatus(302);
        $updateResponse->assertSessionHas('toast_success', 'Admission cycle timeline updated.');
    }

    /**
     * 8. Test Eligibility Rules Management renders formulas and enforces 100% sum validation.
     */
    public function test_eligibility_rules_management_renders_and_validates(): void
    {
        $response = $this->get('/admin/university/eligibility');

        $response->assertStatus(200);
        $response->assertSee('Eligibility Mandates &amp; Merit Formulas', false);
        $response->assertSee('Undergraduate Merit Aggregate Formula');
        $response->assertSee('PEC 60% Pre-Engineering Statutory Minimum');
        $response->assertSee('Statutory Legal Hard Locks');

        // Valid update: 75 + 15 + 10 = 100
        $validResponse = $this->post('/admin/university/eligibility', [
            'net_weight' => 75,
            'fsc_weight' => 15,
            'matric_weight' => 10,
        ]);

        $validResponse->assertStatus(302);
        $validResponse->assertSessionHas('toast_success', 'Merit weightage formula updated successfully.');

        // Invalid update: 70 + 15 + 10 = 95 (!= 100)
        $invalidResponse = $this->post('/admin/university/eligibility', [
            'net_weight' => 70,
            'fsc_weight' => 15,
            'matric_weight' => 10,
        ]);

        $invalidResponse->assertStatus(302);
        $invalidResponse->assertSessionHasErrors(['net_weight']);
    }

    /**
     * 9. Test Scholarship Management renders schemes and registers new aid scheme.
     */
    public function test_scholarship_management_renders_and_stores_scheme(): void
    {
        $response = $this->get('/admin/university/scholarships');

        $response->assertStatus(200);
        $response->assertSee('Scholarships, Endowments &amp; Need-Based Aid', false);
        $response->assertSee('HEC Need-Based Financial Aid Scheme');
        $response->assertSee('NUST Internal Merit Concession Scheme');
        $response->assertSee('Register Aid Scheme');

        // Register new scheme
        $storeResponse = $this->post('/admin/university/scholarships', [
            'name' => 'Women in AI Special Endowment',
            'funding_agency' => 'Ignite National Technology Fund',
            'category' => 'Gender & Diversity',
            'coverage' => '100% Tuition Fee + Research Grant',
            'annual_budget' => 'PKR 18,000,000',
            'beneficiaries_target' => 25,
            'eligibility_criteria' => 'Female undergraduate AI candidates with > 80% FSc marks',
            'application_window' => '01 May to 30 July 2026',
        ]);

        $storeResponse->assertStatus(302);
        $storeResponse->assertSessionHas('toast_success', 'Financial aid scheme registered.');
    }

    /**
     * 10. Test Document Management renders vaults and verifies candidate dossiers.
     */
    public function test_document_management_renders_and_verifies_dossier(): void
    {
        $response = $this->get('/admin/university/documents');

        $response->assertStatus(200);
        $response->assertSee('Document Management &amp; Applicant Verification', false);
        $response->assertSee('Official Institutional Documents Vault');
        $response->assertSee('Applicant Credentials Verification Queue');
        $response->assertSee('Bilal Ahmed');

        // Verify dossier
        $verifyResponse = $this->post('/admin/university/documents/verify/cand-doc-1', [
            'status' => 'verified',
        ]);

        $verifyResponse->assertStatus(302);
        $verifyResponse->assertSessionHas('toast_success', 'Candidate document status updated to Verified.');
    }

    /**
     * 11. Test AI Verification renders source review experience and processes decisions.
     */
    public function test_ai_verification_renders_source_review_and_processes_decisions(): void
    {
        $response = $this->get('/admin/university/ai-verification');

        $response->assertStatus(200);
        $response->assertSee('AI Data Extraction &amp; Source Verification Queue', false);
        $response->assertSee('Current Official Live Value');
        $response->assertSee('Proposed Extracted Modification');
        $response->assertSee('Verbatim Source Context');
        $response->assertSee('Publish to Registry');

        // Approve item 1
        $approveResponse = $this->post('/admin/university/ai-verification/ai-item-1/approve');
        $approveResponse->assertStatus(302);
        $approveResponse->assertSessionHas('toast_success', 'AI-extracted field verified and applied to live institutional record.');

        // Reject item 2
        $rejectResponse = $this->post('/admin/university/ai-verification/ai-item-2/reject');
        $rejectResponse->assertStatus(302);
        $rejectResponse->assertSessionHas('toast_success', 'AI proposed modification rejected. Previous official value preserved.');

        // Edit item 3
        $editResponse = $this->post('/admin/university/ai-verification/ai-item-3/edit', [
            'edited_value' => '18 July 2026',
        ]);
        $editResponse->assertStatus(302);
        $editResponse->assertSessionHas('toast_success', 'Modified value approved and published to live registry.');
    }

    /**
     * 12. Test Student Inquiries renders tickets and dispatches official responses.
     */
    public function test_student_inquiries_renders_and_replies_to_ticket(): void
    {
        $response = $this->get('/admin/university/inquiries');

        $response->assertStatus(200);
        $response->assertSee('Applicant Admissions Inquiries &amp; Triage Desk', false);
        $response->assertSee('Bilal Ahmed Raza');
        $response->assertSee('Fatima Noor Alvi');

        // Dispatch reply
        $replyResponse = $this->post('/admin/university/inquiries/inq-1/reply', [
            'reply' => 'Dear Bilal, yes, under HEC notification SRO 45(I)/2026 you may apply for BS Software Engineering. NUST provides the two deficiency mathematics courses in Semesters 1 and 2.',
        ]);

        $replyResponse->assertStatus(302);
        $replyResponse->assertSessionHas('toast_success', 'Official response dispatched to applicant via email and student portal.');
    }

    /**
     * 13. Test Staff & Settings renders staff roster, audit trails, and updates settings.
     */
    public function test_staff_and_settings_renders_and_updates_preferences(): void
    {
        $response = $this->get('/admin/university/settings');

        $response->assertStatus(200);
        $response->assertSee('Staff Access Control &amp; Security Settings', false);
        $response->assertSee('Dr. Tariq Mahmood');
        $response->assertSee('Prof. Naveed Arshad');
        $response->assertSee('Statutory Activity Audit Trail');

        // Update settings
        $updateResponse = $this->post('/admin/university/settings', [
            'two_factor_required' => 1,
            'auto_ai_scrape_notification' => 1,
            'notify_email' => 'admissions.alerts@nust.edu.pk',
        ]);

        $updateResponse->assertStatus(302);
        $updateResponse->assertSessionHas('toast_success', 'Security protocols and institutional notification settings saved.');
    }

    /**
     * 14. Test Security Boundary: Blocks unauthorized cross-institution access with 403 Forbidden.
     */
    public function test_security_authorization_boundary_blocks_cross_institution_tampering(): void
    {
        // When administrator authorized for NUST attempts to access or modify FAST-NUCES records
        $unauthorizedResponse = $this->get('/admin/university/dashboard?institution=fast-nuces');

        $unauthorizedResponse->assertStatus(403);
        $unauthorizedResponse->assertSee('Unauthorized Institutional Access');
    }
}
