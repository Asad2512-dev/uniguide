<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class NavigationAccessibilityTest extends TestCase
{
    public function test_public_navigation_keeps_guidance_and_admission_destinations_available(): void
    {
        $view = $this->blade('<x-nav.public-navbar />');

        foreach (['universities.index', 'programs.index', 'admissions.index', 'admissions.calendar', 'admissions.checklist', 'scholarships.index', 'compare.index', 'counselor.index', 'eligibility.index', 'marketing.features', 'marketing.how_it_works', 'marketing.about', 'auth.login', 'auth.register'] as $destination) {
            $view->assertSee('href="'.route($destination).'"', false);
        }

        $view->assertDontSee('Rules Engine');
        $view->assertDontSee('Side-by-Side');
        $view->assertSee('Intelligent Tools');
    }

    public function test_mobile_portals_include_secondary_account_and_management_links(): void
    {
        foreach (['student', 'university_admin', 'national_admin'] as $portal) {
            $view = $this->blade('<x-nav.mobile-nav :menu-key="$portal" />', ['portal' => $portal]);

            foreach (config("navigation.$portal.secondary") as $destination) {
                $view->assertSee('href="'.route($destination['route']).'"', false);
            }
        }
    }

    public function test_authenticated_account_menu_submits_to_logout_instead_of_linking_away(): void
    {
        $this->actingAs(User::factory()->make());

        $view = $this->blade('<x-nav.topbar />');

        $view->assertSee('action="'.route('auth.logout').'" method="POST"', false);
        $view->assertSee('name="_token"', false);
        $view->assertSee('href="'.route('student.settings').'"', false);
    }

    public function test_guest_account_menu_offers_sign_in_without_a_logout_action(): void
    {
        $view = $this->blade('<x-nav.topbar />');

        $view->assertSee('href="'.route('auth.login').'"', false);
        $view->assertDontSee('Sign Out');
    }

    public function test_form_controls_associate_validation_errors_and_help_with_the_field(): void
    {
        $input = $this->blade('<x-form.input name="email" label="Email" error="Enter a valid email address." />');
        $input->assertSee('aria-invalid="true"', false);
        $input->assertSee('aria-describedby="email-error"', false);
        $input->assertSee('id="email-error"', false);

        $select = $this->blade('<x-form.select name="province" label="Province" helper-text="Choose your home province." />');
        $select->assertSee('aria-describedby="province-help"', false);
        $select->assertSee('id="province-help"', false);
        $select->assertDontSee('aria-invalid="true"', false);
    }

    public function test_student_mobile_navigation_exposes_five_primary_destinations(): void
    {
        $view = $this->blade('<x-nav.student-bottom-nav />');

        foreach (['student.dashboard', 'universities.index', 'counselor.chat', 'student.saved.universities', 'student.profile'] as $destination) {
            $view->assertSee('href="'.route($destination).'"', false);
        }
        $view->assertSee('aria-label="Student mobile navigation"', false);
    }

    public function test_counselor_workspace_has_labelled_composer_and_no_marketing_footer(): void
    {
        $response = $this->get(route('counselor.chat'));

        $response->assertOk();
        $response->assertSee('Message the UniGuide counselor');
        $response->assertSee('Sign in to send');
        $response->assertDontSee('Institutional Inquiries');
        $response->assertDontSee('Check source links and confirm dates with the university.');
        $response->assertDontSee('Evidence Audit');
    }

    public function test_mobile_comparison_offers_two_labelled_university_selectors(): void
    {
        $response = $this->get(route('compare.results', ['unis' => 'nust,fast-nuces']));

        $response->assertOk();
        $response->assertSee('aria-label="First comparison university"', false);
        $response->assertSee('aria-label="Second comparison university"', false);
    }
}
