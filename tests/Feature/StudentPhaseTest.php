<?php

namespace Tests\Feature;

use Tests\TestCase;

class StudentPhaseTest extends TestCase
{
    /**
     * 1. Test Onboarding wizard renders and processes submission.
     */
    public function test_student_onboarding_renders_and_processes_submission(): void
    {
        $response = $this->get('/student/onboarding');
        $response->assertStatus(200);
        $response->assertSee('Select Your Academic Stream');
        $response->assertSee('FSc Pre-Engineering');
        $response->assertSee('Matriculation / O-Level Percentage', false);
        $response->assertSee('Preferred Degree Programs');

        // Successful submission
        $postResponse = $this->post('/student/onboarding', [
            'stream' => 'pre_engineering',
            'matric_percentage' => 88.5,
            'fsc_percentage' => 82.0,
            'target_cities' => ['Islamabad / Rawalpindi', 'Lahore'],
            'target_disciplines' => ['Computer Science & AI', 'Software Engineering'],
            'budget_max' => 220000,
            'scholarship_interest' => 'need_and_merit',
        ]);

        $postResponse->assertStatus(302);
        $postResponse->assertRedirect(route('student.dashboard'));

        // Validation error
        $invalidResponse = $this->post('/student/onboarding', [
            'stream' => '',
        ]);
        $invalidResponse->assertStatus(302);
        $invalidResponse->assertSessionHasErrors(['stream', 'matric_percentage', 'fsc_percentage']);
    }

    /**
     * 2. Test Student Dashboard renders personal command center.
     */
    public function test_student_dashboard_renders(): void
    {
        $response = $this->get('/student/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Welcome back, Ahmad Raza Khan');
        $response->assertSee('Profile Completeness');
        $response->assertSee('Active Applications Tracker');
        $response->assertSee('Statutory Aggregates Scorecard');
        $response->assertSee('Recommended Universities for You');
        $response->assertSee('Upcoming Deadlines');
        $response->assertSee('Financial Aid Matches');
    }

    /**
     * 3. Test Academic Profile renders and updates educational credentials.
     */
    public function test_academic_profile_renders_and_updates(): void
    {
        $response = $this->get('/student/profile');
        $response->assertStatus(200);
        $response->assertSee('Academic Profile & Qualifications', false);
        $response->assertSee('Candidate Identification');
        $response->assertSee('Matriculation / O-Level', false);
        $response->assertSee('FSc Pre-Engineering / HSSC', false);
        $response->assertSee('Standardized Entry Test Scores');
        $response->assertSee('Admission Documents Locker');

        // Update profile
        $updateResponse = $this->post('/student/profile', [
            'full_name' => 'Ahmad Raza Khan',
            'phone' => '+92 300 5558841',
            'city' => 'Islamabad',
            'matric_percentage' => 89.2,
            'fsc_percentage' => 83.0,
            'matric_board' => 'FBISE',
            'matric_roll' => '284192',
            'fsc_board' => 'FBISE',
            'fsc_roll' => '719204',
        ]);

        $updateResponse->assertStatus(302);
        $updateResponse->assertRedirect(route('student.profile'));
    }

    /**
     * 4. Test Personalized Recommendations screen renders customized tiers.
     */
    public function test_personalized_recommendations_renders_reach_match_safety(): void
    {
        $response = $this->get('/student/recommendations');
        $response->assertStatus(200);
        $response->assertSee('Personalized University Recommendations');
        $response->assertSee('Reach Programs');
        $response->assertSee('Target Match Programs');
        $response->assertSee('Safety Programs');
        $response->assertSee('Calculated Aggregate');
    }

    /**
     * 5. Test Saved Universities collection and removal.
     */
    public function test_saved_universities_collection_and_removal(): void
    {
        $response = $this->get('/student/saved/universities');
        $response->assertStatus(200);
        $response->assertSee('Shortlisted Universities');
        $response->assertSee('National University of Sciences & Technology', false);
        $response->assertSee('Sort by:');

        // Test sorting
        $responseFee = $this->get('/student/saved/universities?sort=fee_low');
        $responseFee->assertStatus(200);

        // Delete saved university
        $deleteResponse = $this->delete('/student/saved/universities/lums');
        $deleteResponse->assertStatus(302);
        $deleteResponse->assertRedirect(route('student.saved.universities'));
    }

    /**
     * 6. Test Saved Programs collection and removal.
     */
    public function test_saved_programs_collection_and_removal(): void
    {
        $response = $this->get('/student/saved/programs');
        $response->assertStatus(200);
        $response->assertSee('Saved Degree Programs');
        $response->assertSee('BS Computer Science');

        // Delete saved program
        $deleteResponse = $this->delete('/student/saved/programs/bs-computer-science-nust');
        $deleteResponse->assertStatus(302);
        $deleteResponse->assertRedirect(route('student.saved.programs'));
    }

    /**
     * 7. Test Saved Scholarships collection and removal.
     */
    public function test_saved_scholarships_collection_and_removal(): void
    {
        $response = $this->get('/student/saved/scholarships');
        $response->assertStatus(200);
        $response->assertSee('Saved Scholarships & Financial Aid', false);
        $response->assertSee('Income Eligibility Verified');
        $response->assertSee('HEC Need-Based Undergraduate Scholarship');

        // Delete saved scholarship
        $deleteResponse = $this->delete('/student/saved/scholarships/peef-scholarship');
        $deleteResponse->assertStatus(302);
        $deleteResponse->assertRedirect(route('student.saved.scholarships'));
    }

    /**
     * 8. Test My Admission Tracker CRUD and lifecycle stages.
     */
    public function test_admission_tracker_crud_and_lifecycle_stages(): void
    {
        // View tracker
        $response = $this->get('/student/tracker');
        $response->assertStatus(200);
        $response->assertSee('My Admission Tracker');
        $response->assertSee('Candidate-Managed Admission Tracker');
        $response->assertSee('All Applications');

        // Add application
        $addResponse = $this->post('/student/tracker', [
            'university_name' => 'University of Engineering and Technology, Lahore',
            'university_acronym' => 'UET Lahore',
            'university_slug' => 'uet-lahore',
            'program_name' => 'BSc Computer Engineering',
            'campus' => 'Main Campus, Lahore',
            'stage' => 'applied',
            'deadline' => '30 June 2026',
            'application_ref' => 'UET-2026-9912',
            'notes' => 'ECAT result pending normalization.',
        ]);

        $addResponse->assertStatus(302);
        $addResponse->assertRedirect(route('student.tracker'));

        // Update application
        $updateResponse = $this->put('/student/tracker/app-1', [
            'stage' => 'offer_received',
            'fee_challan_status' => 'Paid Full 1st Semester',
            'notes' => 'Selected in 1st Merit List.',
        ]);

        $updateResponse->assertStatus(302);
        $updateResponse->assertRedirect(route('student.tracker'));

        // Delete application
        $deleteResponse = $this->delete('/student/tracker/app-5');
        $deleteResponse->assertStatus(302);
        $deleteResponse->assertRedirect(route('student.tracker'));
    }

    /**
     * 9. Test Notifications Center and read actions.
     */
    public function test_notifications_center_filtering_and_read_actions(): void
    {
        // View all notifications
        $response = $this->get('/student/notifications');
        $response->assertStatus(200);
        $response->assertSee('Notifications & Admission Alerts', false);
        $response->assertSee('NUST NET-4 Registration Closes');

        // Filter notifications
        $responseFilter = $this->get('/student/notifications?filter=deadline');
        $responseFilter->assertStatus(200);
        $responseFilter->assertSee('NUST NET-4 Registration Closes');

        // Mark single as read
        $readResponse = $this->post('/student/notifications/notif-1/read');
        $readResponse->assertStatus(302);
        $readResponse->assertRedirect(route('student.notifications'));

        // Mark all as read
        $allReadResponse = $this->post('/student/notifications/read-all');
        $allReadResponse->assertStatus(302);
        $allReadResponse->assertRedirect(route('student.notifications'));
    }

    /**
     * 10. Test Account Settings renders and updates.
     */
    public function test_account_settings_renders_and_updates(): void
    {
        $response = $this->get('/student/settings');
        $response->assertStatus(200);
        $response->assertSee('Account Settings & Security', false);
        $response->assertSee('Candidate Information');
        $response->assertSee('Notification Preferences');
        $response->assertSee('Security & Authentication', false);
        $response->assertSee('Data Privacy & Archival', false);

        // Update settings
        $updateResponse = $this->post('/student/settings', [
            'full_name' => 'Ahmad Raza Khan',
            'phone' => '+92 300 5558841',
            'city' => 'Rawalpindi',
        ]);

        $updateResponse->assertStatus(302);
        $updateResponse->assertRedirect(route('student.settings'));
    }
}
