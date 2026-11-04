<?php

namespace Tests\Feature;

use Tests\TestCase;

class NationalAdminPhaseTest extends TestCase
{
    /**
     * 1. National Overview Dashboard.
     */
    public function test_national_admin_dashboard_renders_kpis_and_regional_breakdown(): void
    {
        $response = $this->get('/admin/national/dashboard');

        $response->assertStatus(200);
        $response->assertSee('National Operational Command');
        $response->assertSee('Total Indexed Universities');
        $response->assertSee('Verified Institutions');
        $response->assertSee('Provincial Jurisdiction');
        $response->assertSee('Punjab');
        $response->assertSee('Sindh');
        $response->assertSee('Recent Data Ingestions');
    }

    /**
     * 2. University Registry.
     */
    public function test_national_university_registry_renders_chartered_universities(): void
    {
        $response = $this->get('/admin/national/universities');

        $response->assertStatus(200);
        $response->assertSee('National University Registry');
        $response->assertSee('National University of Sciences & Technology', false);
        $response->assertSee('FAST-NUCES');
        $response->assertSee('Lahore University of Management Sciences');
        $response->assertSee('Total Chartered');
    }

    /**
     * 3. University Approval Center.
     */
    public function test_university_approval_center_renders_and_processes_charter_status(): void
    {
        $response = $this->get('/admin/national/approvals');

        $response->assertStatus(200);
        $response->assertSee('University Approval Center');
        $response->assertSee('FAST-NUCES (Proposed Multan Campus)');
        $response->assertSee('Constituent Sub-Campus Endorsement');

        // Process approval action
        $processResponse = $this->post('/admin/national/approvals/appr-1', [
            'action' => 'approved',
        ]);

        $processResponse->assertStatus(302);
        $processResponse->assertSessionHas('toast_success');
    }

    /**
     * 4. Campus and Program Registry.
     */
    public function test_campus_and_program_registry_renders_accredited_programs(): void
    {
        $response = $this->get('/admin/national/programs');

        $response->assertStatus(200);
        $response->assertSee('Campus and Program Registry');
        $response->assertSee('BS Computer Science');
        $response->assertSee('BE Electrical Engineering');
        $response->assertSee('NCEAC Category W');
    }

    /**
     * 5. Official Data Source Management.
     */
    public function test_official_data_source_management_renders_authoritative_feeds(): void
    {
        $response = $this->get('/admin/national/sources');

        $response->assertStatus(200);
        $response->assertSee('Official Data Source Management');
        $response->assertSee('NUST Official Admissions Portal');
        $response->assertSee('FAST-NUCES Central University Portal');
        $response->assertSee('Pakistan Engineering Council');
    }

    /**
     * 6. Web Scraping Control Center.
     */
    public function test_web_scraping_control_center_renders_and_triggers_crawls(): void
    {
        $response = $this->get('/admin/national/scraping');

        $response->assertStatus(200);
        $response->assertSee('Web Scraping Control Center');
        $response->assertSee('Active Crawlers');
        $response->assertSee('Queue Depth');

        // Trigger manual crawl
        $triggerResponse = $this->post('/admin/national/scraping/trigger', [
            'source_name' => 'NUST Islamabad Prospectus',
        ]);

        $triggerResponse->assertStatus(302);
        $triggerResponse->assertSessionHas('toast_success');

        // Retry failed crawls
        $retryResponse = $this->post('/admin/national/scraping/retry-failed');
        $retryResponse->assertStatus(302);
        $retryResponse->assertSessionHas('toast_success');
    }

    /**
     * 7. Scraping Job History.
     */
    public function test_scraping_job_history_renders_terminal_logs(): void
    {
        $response = $this->get('/admin/national/scraping/history');

        $response->assertStatus(200);
        $response->assertSee('Scraping Job History');
        $response->assertSee('JOB-CRW-9842');
        $response->assertSee('NUST Islamabad Central Admissions');
    }

    /**
     * 8. AI Extraction Review Workspace.
     */
    public function test_ai_extraction_review_workspace_renders_and_records_decisions(): void
    {
        $response = $this->get('/admin/national/ai-extraction');

        $response->assertStatus(200);
        $response->assertSee('AI Extraction Review Workspace');
        $response->assertSee('BS Artificial Intelligence Semester Tuition');
        $response->assertSee('PKR 195,000');
        $response->assertSee('PKR 180,000');

        // Approve extraction
        $actionResponse = $this->post('/admin/national/ai-extraction/ext-1', [
            'action' => 'approved',
        ]);

        $actionResponse->assertStatus(302);
        $actionResponse->assertSessionHas('toast_success');
    }

    /**
     * 9. Data Verification Queue.
     */
    public function test_data_verification_queue_renders_staging_items(): void
    {
        $response = $this->get('/admin/national/verification');

        $response->assertStatus(200);
        $response->assertSee('Data Verification Queue');
        $response->assertSee('NUST Islamabad');
        $response->assertSee('Pending Review');
    }

    /**
     * 10. Knowledge Base Management.
     */
    public function test_knowledge_base_management_renders_and_triggers_reindexing(): void
    {
        $response = $this->get('/admin/national/knowledge-base');

        $response->assertStatus(200);
        $response->assertSee('Knowledge Base Management (RAG)');
        $response->assertSee('Pakistan Engineering Council (PEC) Act', false);
        $response->assertSee('Embedded Vectors');

        // Reindex trigger
        $reindexResponse = $this->post('/admin/national/knowledge-base/reindex');
        $reindexResponse->assertStatus(302);
        $reindexResponse->assertSessionHas('toast_success');
    }

    /**
     * 11. AI Chatbot Configuration.
     */
    public function test_chatbot_config_renders_and_updates_statutory_hyperparameters(): void
    {
        $response = $this->get('/admin/national/chatbot-config');

        $response->assertStatus(200);
        $response->assertSee('AI Chatbot Configuration');
        $response->assertSee('Temperature (Factual Precision)');
        $response->assertSee('PEC Washington Accord Ruleset');

        // Update configuration
        $updateResponse = $this->post('/admin/national/chatbot-config', [
            'model' => 'gemini-1.5-pro',
            'temperature' => 0.25,
            'max_tokens' => 2048,
            'citation_threshold' => 0.88,
            'system_prompt' => 'Updated national prompt for admissions counselor.',
            'inject_pec_rules' => '1',
            'enforce_strict_citations' => '1',
        ]);

        $updateResponse->assertStatus(302);
        $updateResponse->assertSessionHas('toast_success');
    }

    /**
     * 12. Student and Admin User Management.
     */
    public function test_student_and_admin_user_management_renders_directory(): void
    {
        $response = $this->get('/admin/national/users');

        $response->assertStatus(200);
        $response->assertSee('Student and Admin User Management');
        $response->assertSee('Dr. Tariq Mahmood');
        $response->assertSee('Prof. Dr. Mukhtar Ahmed');
    }

    /**
     * 13. Notifications Management.
     */
    public function test_notifications_management_renders_and_dispatches_broadcasts(): void
    {
        $response = $this->get('/admin/national/notifications');

        $response->assertStatus(200);
        $response->assertSee('Regulatory Broadcasts');
        $response->assertSee('Federal Directive: Pre-Medical to Computing');

        // Dispatch new broadcast
        $dispatchResponse = $this->post('/admin/national/notifications', [
            'title' => 'Emergency Test Window Notice',
            'category' => 'National Admissions Alert',
            'target_audience' => 'All Chartered Universities & Candidates',
        ]);

        $dispatchResponse->assertStatus(302);
        $dispatchResponse->assertSessionHas('toast_success');
    }

    /**
     * 14. Analytics and Reports.
     */
    public function test_analytics_and_reports_renders_macro_intelligence(): void
    {
        $response = $this->get('/admin/national/analytics');

        $response->assertStatus(200);
        $response->assertSee('National Higher Education Analytics');
        $response->assertSee('Undergraduate Discipline Applicant Demand');
        $response->assertSee('Computer Science & AI');
        $response->assertSee('Inter-Provincial Student Migration Flow');
    }

    /**
     * 15. System Settings and Audit Logs.
     */
    public function test_settings_and_audit_logs_renders_and_updates_concurrency(): void
    {
        $response = $this->get('/admin/national/settings');

        $response->assertStatus(200);
        $response->assertSee('System Settings and Audit Logs');
        $response->assertSee('Crawler Worker Concurrency');
        $response->assertSee('Immutable Supervisory Audit Trail');

        // Update settings
        $updateResponse = $this->post('/admin/national/settings', [
            'crawler_concurrency' => 12,
            'api_rate_limit_per_minute' => 800,
            'immutable_audit_logging_enabled' => '1',
        ]);

        $updateResponse->assertStatus(302);
        $updateResponse->assertSessionHas('toast_success');
    }

    /**
     * 16. Security & Authorization Boundary.
     */
    public function test_unauthorized_user_is_blocked_from_national_admin(): void
    {
        // When authenticated or identified as a student role, access should be forbidden (403)
        $response = $this->get('/admin/national/dashboard?auth_role=student');
        $response->assertStatus(403);
    }
}
