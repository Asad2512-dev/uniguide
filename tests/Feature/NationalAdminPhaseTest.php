<?php

namespace Tests\Feature;

use App\Jobs\ProcessWebScrapeJob;
use App\Models\AiExtraction;
use App\Models\DataSource;
use App\Models\Notification;
use App\Models\SystemSetting;
use App\Models\User;
use App\Models\VerificationRequest;
use Illuminate\Support\Facades\Queue;
use Tests\DatabaseFeatureTestCase;

class NationalAdminPhaseTest extends DatabaseFeatureTestCase
{
    public function test_guests_and_students_cannot_open_platform_management(): void
    {
        $this->get('/admin/platform/dashboard')->assertRedirect();
        $this->loginAs('student@gmail.com');
        $this->get('/admin/platform/dashboard')->assertForbidden();
    }

    public function test_platform_management_pages_render_actual_records(): void
    {
        $this->loginAs('admin@gmail.com');
        foreach (['dashboard', 'universities', 'applications', 'programs', 'sources', 'scraping', 'scraping/history', 'ai-extraction', 'verification', 'knowledge-base', 'chatbot-config', 'users', 'notifications', 'analytics', 'settings'] as $path) {
            $this->get('/admin/platform/'.$path)->assertOk();
        }
        $this->get('/admin/platform/users')->assertSee('numl@gmail.com')->assertDontSee('chairman@hec.gov.pk');
    }

    public function test_source_creation_and_collection_queue_are_real(): void
    {
        $this->loginAs('admin@gmail.com');
        $this->post('/admin/platform/sources', ['name' => 'Source test', 'institution' => 'NUML', 'base_url' => 'https://numl.edu.pk'])->assertRedirect();
        $this->assertSame('pending', DataSource::withoutGlobalScopes()->where('name', 'Source test')->value('status'));
        Queue::fake();
        $this->post('/admin/platform/scraping/trigger', ['source_name' => 'Source test'])->assertRedirect();
        Queue::assertPushed(ProcessWebScrapeJob::class);
    }

    public function test_extraction_and_verification_reviews_are_persisted(): void
    {
        $this->loginAs('admin@gmail.com');
        $e = AiExtraction::create(['extraction_code' => 'TEST-EX', 'university_name' => 'NUML', 'institution_slug' => 'numl', 'entity_type' => 'university', 'field_name' => 'phone', 'after_value' => '123', 'confidence_score' => 0, 'status' => 'pending']);
        $this->post('/admin/platform/ai-extraction/'.$e->id, ['decision' => 'approved'])->assertRedirect();
        $this->assertSame('approved', $e->fresh()->status);
        $v = VerificationRequest::create(['queue_code' => 'TEST-V', 'institution_name' => 'NUML', 'institution_slug' => 'numl', 'entity_type' => 'university', 'field_name' => 'phone', 'status' => 'pending', 'priority' => 'normal']);
        $this->post('/admin/platform/verification/'.$v->id, ['action' => 'approved'])->assertRedirect();
        $this->assertSame('approved', $v->fresh()->status);
    }

    public function test_in_app_broadcasts_reach_only_the_requested_users(): void
    {
        $this->loginAs('admin@gmail.com');
        $this->post('/admin/platform/notifications', ['title' => 'Real update', 'message' => 'Please check your saved records.', 'category' => 'system', 'target_audience' => 'students'])->assertRedirect();
        $this->assertSame(1, Notification::where('title', 'Real update')->count());
        $this->assertSame(User::where('email', 'student@gmail.com')->value('id'), Notification::first()->user_id);
    }

    public function test_settings_survive_sessions_and_unconfigured_integrations_do_not_claim_success(): void
    {
        $this->loginAs('admin@gmail.com');
        $this->post('/admin/platform/settings', ['crawler_concurrency' => 2, 'api_rate_limit_per_minute' => 40])->assertRedirect();
        $this->assertSame(2, SystemSetting::get('platform.settings')['crawler_concurrency']);
        $this->post('/admin/platform/knowledge-base/reindex')->assertSessionHasErrors('knowledge_base');
        $this->post('/admin/platform/users/invite', ['name' => 'New user', 'email' => 'new@example.com', 'role' => 'student', 'scope' => 'platform'])->assertSessionHasErrors('email');
        $this->assertSame(3, User::count());
    }
}
