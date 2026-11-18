<?php

namespace Tests\Feature;

use App\Models\Inquiry;
use App\Models\User;
use Tests\DatabaseFeatureTestCase;

class FrontendArchitectureTest extends DatabaseFeatureTestCase
{
    public function test_public_pages_render_with_live_catalog_and_no_fabricated_identity(): void
    {
        foreach (['/', '/about', '/features', '/contact', '/how-it-works', '/login', '/register', '/forgot-password', '/universities/join'] as $path) {
            $this->get($path)->assertOk();
        }
        $this->get('/')->assertSee('Find your university in Punjab or Islamabad.')->assertDontSee('1042/1100')->assertDontSee('482/520');
    }

    public function test_contact_form_creates_a_database_record_and_validates_input(): void
    {
        $this->post('/contact', ['name' => 'A student', 'email' => 'contact@example.com', 'subject' => 'Help needed', 'message' => 'Please help me find the correct university page.'])->assertRedirect();
        $this->assertSame('Help needed', Inquiry::firstOrFail()->subject);
        $this->post('/contact', ['email' => 'invalid'])->assertSessionHasErrors(['email', 'name', 'message']);
    }

    public function test_recovery_does_not_claim_to_send_email_without_a_mail_service(): void
    {
        $this->post('/forgot-password', ['email' => 'student@gmail.com'])->assertSessionHasErrors('email');
    }

    public function test_only_three_seeded_accounts_and_correct_workspace_redirects(): void
    {
        $this->assertSame(3, User::count());
        foreach (['student@gmail.com' => 'student.dashboard', 'numl@gmail.com' => 'uni_admin.dashboard', 'admin@gmail.com' => 'national_admin.dashboard'] as $email => $route) {
            $this->post('/login', ['email' => $email, 'password' => '12345678'])->assertRedirect(route($route));
            $this->post('/logout');
        }
    }

    public function test_pages_include_brand_favicon_and_assets_exist(): void
    {
        $this->get('/')->assertSee('favicon', false);
        $this->assertFileExists(public_path('favicon.svg'));
    }
}
