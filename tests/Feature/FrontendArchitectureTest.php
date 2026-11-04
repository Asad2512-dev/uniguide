<?php

namespace Tests\Feature;

use Tests\TestCase;

class FrontendArchitectureTest extends TestCase
{
    /**
     * Test the home route loads the Design System Living Catalog.
     */
    public function test_home_page_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('UniGuide Pakistan');
        $response->assertSee('Global Design System');
    }

    /**
     * Test the dedicated /design-system route loads successfully.
     */
    public function test_design_system_showcase_loads_successfully(): void
    {
        $response = $this->get('/design-system');

        $response->assertStatus(200);
        $response->assertSee('UniGuide Pakistan Design System');
        $response->assertSee('Color Architecture & Typography');
        $response->assertSee('Buttons, Icon Buttons & Segmented Controls');
        $response->assertSee('Form Fields, Multi-Selects & Filters');
        $response->assertSee('Badges, Admission States & Statutory Seals');
        $response->assertSee('University Discovery Cards');
        $response->assertSee('AI Admission Guidance & Merit Intelligence');
        $response->assertSee('Tables, Pagination, Timelines & Steps');
        $response->assertSee('Modals, Drawers, Toasts, Skeletons & Errors');
        $response->assertSee('Chart.js Charts & Analytical Graphs');
    }

    /**
     * Test Shell 1: Public Marketing & Discovery Layout.
     */
    public function test_public_discovery_shell_loads_successfully(): void
    {
        $response = $this->get(route('preview.public'));

        $response->assertStatus(200);
        $response->assertSee('Discover verified Pakistani universities');
        $response->assertSee('Recognized Universities');
        $response->assertSee('Featured National Universities');
    }

    /**
     * Test Shell 2: Authenticated Student Portal Layout.
     */
    public function test_student_portal_shell_loads_successfully(): void
    {
        $response = $this->get(route('preview.student'));

        $response->assertStatus(200);
        $response->assertSee('Student Admissions Command');
        $response->assertSee('Calculated Aggregate');
        $response->assertSee('My Submitted University Applications');
    }

    /**
     * Test Shell 3: University Institutional Administrator Layout.
     */
    public function test_university_admin_shell_loads_successfully(): void
    {
        $response = $this->get(route('preview.university_admin'));

        $response->assertStatus(200);
        $response->assertSee('Institutional Admissions Console');
        $response->assertSee('Quota Allocations');
        $response->assertSee('National University of Sciences & Technology', false);
    }

    /**
     * Test Shell 4: National Super Administrator (HEC) Layout.
     */
    public function test_national_admin_shell_loads_successfully(): void
    {
        $response = $this->get(route('preview.national_admin'));

        $response->assertStatus(200);
        $response->assertSee('National Higher Education Command Console');
        $response->assertSee('Federal Higher Education Registry');
        $response->assertSee('HEC Statutory Authority');
    }

    /**
     * Test navigation configuration structure.
     */
    public function test_navigation_config_is_valid(): void
    {
        $this->assertIsArray(config('navigation.public'));
        $this->assertIsArray(config('navigation.student.main'));
        $this->assertIsArray(config('navigation.university_admin.main'));
        $this->assertIsArray(config('navigation.national_admin.main'));
    }
}
