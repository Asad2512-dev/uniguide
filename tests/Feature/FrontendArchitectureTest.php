<?php

namespace Tests\Feature;

use Tests\TestCase;

class FrontendArchitectureTest extends TestCase
{
    /**
     * Test the Homepage renders with all required product sections.
     */
    public function test_homepage_renders_all_required_sections(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        // Header & Navigation
        $response->assertSee('UniGuide');
        $response->assertSee('PAKISTAN');
        
        // Editorial Hero & Central Intelligent Search
        $response->assertSee('Find the right university in Pakistan', false);
        $response->assertSee('Calculate exact merit', false);
        $response->assertSee('By University');
        $response->assertSee('By Degree Program');
        $response->assertSee('By My Aggregate %');

        // Quick Access Entry Tiles
        $response->assertSee('248+ HEC Universities');
        $response->assertSee('6,400+ Degree Programs');
        $response->assertSee('PKR 4.2B+ Scholarships');

        // Featured Universities Section
        $response->assertSee('Explore Verified Pakistani Universities');
        $response->assertSee('National University of Sciences');
        $response->assertSee('FAST - National University of Computer');
        $response->assertSee('Lahore University of Management Sciences');

        // Degree Programs Exploration
        $response->assertSee('Popular Undergraduate Degree Programs');
        $response->assertSee('BS Computer Science (BSCS)');

        // AI Counselor Product Showcase
        $response->assertSee('An AI Counselor grounded strictly in verified Pakistani admission guidelines');
        $response->assertSee('UniGuide National Merit Intelligence');

        // University Comparison Showcase
        $response->assertSee('Side-by-Side Institutional Comparison');
        $response->assertSee('Evaluation Parameter');

        // Scholarship Discovery Section
        $response->assertSee('National Scholarships &amp; Grants', false);
        $response->assertSee('HEC Need-Based Undergraduate Scholarship');

        // How the Platform Works
        $response->assertSee('How Pakistani Students Navigate Admissions with UniGuide');
        $response->assertSee('Discover & Filter', false);

        // Trust & Data Transparency
        $response->assertSee('Higher Education Commission of Pakistan Ordinance 2002');
        $response->assertSee('Statutory Verification Seal');

        // Final Conversion Section
        $response->assertSee('Join thousands of Pakistani students finding their future on UniGuide');
    }

    /**
     * Test Screen 2: About UniGuide screen.
     */
    public function test_about_page_renders_successfully(): void
    {
        $response = $this->get(route('marketing.about'));

        $response->assertStatus(200);
        $response->assertSee('About UniGuide Pakistan');
        $response->assertSee('The UniGuide Zero-Hallucination', false);
        $response->assertSee('Evolution of the National Platform');
        $response->assertSee('Academic Advisory Council');
    }

    /**
     * Test Screen 3: How It Works screen.
     */
    public function test_how_it_works_page_renders_successfully(): void
    {
        $response = $this->get(route('marketing.how_it_works'));

        $response->assertStatus(200);
        $response->assertSee('How Admissions Work on UniGuide');
        $response->assertSee('Standard Pakistani Aggregate Merit Formulas');
        $response->assertSee('NUST Entry Test (NET)');
        $response->assertSee('FAST-NUCES (NU Test)');
        $response->assertSee('Medical & Dental (MDCAT)', false);
    }

    /**
     * Test Screen 4: Platform Features screen.
     */
    public function test_features_page_renders_successfully(): void
    {
        $response = $this->get(route('marketing.features'));

        $response->assertStatus(200);
        $response->assertSee('UniGuide Platform Capabilities');
        $response->assertSee('Intelligent Multi-Parameter University Search');
        $response->assertSee('Algorithmic Aggregate Merit Calculator');
        $response->assertSee('Integrated with 1-Link, 1-Bill, and National Banking Systems');
    }

    /**
     * Test Screen 5: Contact and Support screen.
     */
    public function test_contact_page_renders_and_handles_submission(): void
    {
        $response = $this->get(route('marketing.contact'));

        $response->assertStatus(200);
        $response->assertSee('Admissions Support');
        $response->assertSee('Emergency Admissions Helpline');
        $response->assertSee('Federal &amp; ICT Headquarters', false);

        // Test POST submission
        $postResponse = $this->post(route('marketing.contact.submit'), [
            'name' => 'Usman Tariq',
            'email' => 'usman.tariq@gmail.com',
            'phone' => '03001234567',
            'category' => 'aggregate',
            'subject' => 'Inquiry regarding NUST NET-3 cutoff aggregate calculation',
            'message' => 'I have scored 940/1100 in Matric and 480/520 in FSc Part-I. What is my probability for Software Engineering?',
        ]);

        $postResponse->assertRedirect(route('marketing.contact'));
        $postResponse->assertSessionHas('success');
    }

    /**
     * Test Screen 6a: Shared Authentication Login.
     */
    public function test_login_page_renders_and_handles_role_switching(): void
    {
        $response = $this->get(route('auth.login'));

        $response->assertStatus(200);
        $response->assertSee('Sign in to UniGuide');
        $response->assertSee('Select Workspace Account Type');

        // Test Student Login Submission
        $postStudent = $this->post(route('auth.login.submit'), [
            'role' => 'student',
            'email' => 'ahmad.raza@student.uniguide.pk',
            'password' => 'StudentPass2026!',
        ]);
        $postStudent->assertRedirect(route('preview.student'));

        // Test University Admin Login Submission
        $postUniv = $this->post(route('auth.login.submit'), [
            'role' => 'university',
            'email' => 'admissions@nust.edu.pk',
            'password' => 'NustAdmin2026!',
        ]);
        $postUniv->assertRedirect(route('preview.university_admin'));

        // Test HEC Admin Login Submission
        $postAdmin = $this->post(route('auth.login.submit'), [
            'role' => 'admin',
            'email' => 'regulatory@hec.gov.pk',
            'password' => 'HecFederal2026!',
        ]);
        $postAdmin->assertRedirect(route('preview.national_admin'));
    }

    /**
     * Test Screen 6b: Candidate Registration.
     */
    public function test_register_page_renders_and_validates(): void
    {
        $response = $this->get(route('auth.register'));

        $response->assertStatus(200);
        $response->assertSee('Create Your Student Account');
        $response->assertSee('Intermediate Academic Stream');

        // Test Successful Registration
        $postResponse = $this->post(route('auth.register.submit'), [
            'full_name' => 'Zainab Bibi',
            'email' => 'zainab.bibi@gmail.com',
            'phone' => '03211234567',
            'stream' => 'fsc_pre_eng',
            'province' => 'punjab',
            'password' => 'SecurePass2026!',
            'password_confirmation' => 'SecurePass2026!',
            'terms' => '1',
        ]);

        $postResponse->assertRedirect(route('preview.student'));
    }

    /**
     * Test Screen 6c: Forgot Password.
     */
    public function test_forgot_password_page_renders_and_submits(): void
    {
        $response = $this->get(route('auth.forgot_password'));

        $response->assertStatus(200);
        $response->assertSee('Reset Your Password');

        $postResponse = $this->post(route('auth.forgot_password.submit'), [
            'email' => 'zainab.bibi@gmail.com',
        ]);

        $postResponse->assertRedirect(route('auth.login'));
    }

    /**
     * Test Design System Showcase and Shell Previews remain functional.
     */
    public function test_design_system_and_shells_continue_to_pass(): void
    {
        $this->get('/design-system')->assertStatus(200);
        $this->get(route('preview.public'))->assertStatus(200);
        $this->get(route('preview.student'))->assertStatus(200);
        $this->get(route('preview.university_admin'))->assertStatus(200);
        $this->get(route('preview.national_admin'))->assertStatus(200);
    }
}
