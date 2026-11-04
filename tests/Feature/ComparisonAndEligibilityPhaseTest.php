<?php

namespace Tests\Feature;

use Tests\TestCase;

class ComparisonAndEligibilityPhaseTest extends TestCase
{
    /**
     * 1. Test University Comparison Builder renders presets and selectable universities.
     */
    public function test_comparison_builder_renders_presets_and_selectable_universities(): void
    {
        $response = $this->get('/compare/builder');

        $response->assertStatus(200);
        $response->assertSee('University Comparison Builder');
        $response->assertSee('Institutional Benchmark Workspace');
        $response->assertSee('Curated Benchmark Presets');
        $response->assertSee('National Computing Big-4 Battle');
        $response->assertSee('Top Engineering &amp; Technology Charters', false);
        $response->assertSee('Premier Business &amp; Management Schools', false);
        $response->assertSee('Medical &amp; Allied Biosciences Benchmark', false);
        $response->assertSee('Build Custom Comparison');
        $response->assertSee('Launch Comparison');
    }

    /**
     * 2. Test Side-by-Side Comparison Results renders matrix, metrics, and sticky headers.
     */
    public function test_comparison_results_renders_side_by_side_matrix(): void
    {
        // Test default route without parameters
        $defaultResponse = $this->get('/compare');
        $defaultResponse->assertStatus(200);
        $defaultResponse->assertSee('Side-by-Side Institutional Benchmark');

        // Test explicit parameters
        $response = $this->get('/compare/results?unis=nust,fast-nuces,giki');
        $response->assertStatus(200);
        $response->assertSee('Side-by-Side Institutional Benchmark');
        $response->assertSee('Highlight Differences');
        $response->assertSee('NUST');
        $response->assertSee('FAST-NUCES');
        $response->assertSee('GIKI');
        $response->assertSee('Verified Tuition &amp; Fees', false);
        $response->assertSee('Admission Policy &amp; Entry Tests', false);
        $response->assertSee('Financial Aid &amp; Endowment Support', false);
        $response->assertSee('Campus Life, Hostels &amp; Location', false);
        $response->assertSee('Official Statutory Reference &amp; Audit', false);
    }

    /**
     * 3. Test Academic Eligibility Wizard renders all evaluation steps and criteria.
     */
    public function test_eligibility_wizard_renders_academic_steps(): void
    {
        $response = $this->get('/eligibility');

        $response->assertStatus(200);
        $response->assertSee('Academic Eligibility Checker');
        $response->assertSee('Verified Statutory Rules Engine');
        $response->assertSee('Select Your Qualification Type');
        $response->assertSee('FSc (Pre-Engineering / Pre-Medical)');
        $response->assertSee('Cambridge A-Levels');
        $response->assertSee('DAE (Diploma of Associate Engineering)');
        $response->assertSee('Enter Academic Marks');
        $response->assertSee('Check Official Eligibility');
    }

    /**
     * 4. Test Eligibility Results calculates verified eligibility for Pre-Engineering student.
     */
    public function test_eligibility_results_evaluates_pre_engineering_candidate(): void
    {
        $response = $this->get('/eligibility/results?'.http_build_query([
            'qualification' => 'fsc',
            'academic_group' => 'pre_engineering',
            'matric_percentage' => 88.5,
            'fsc_percentage' => 81.2,
            'desired_program' => 'computing',
            'preferred_location' => 'all',
            'test_type' => 'net',
            'test_score' => 152,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Official Academic Eligibility Results');
        $response->assertSee('Verified Backend Rules Engine');
        $response->assertSee('Eligible Programs');
        $response->assertSee('Save to Shortlist');
        $response->assertSee('View Shortlist Portfolio');
    }

    /**
     * 5. Test Eligibility Results applies HEC SRO 45(I)/2026 remedial math condition for Pre-Medical.
     */
    public function test_eligibility_results_handles_pre_medical_computing_transition(): void
    {
        $response = $this->get('/eligibility/results?'.http_build_query([
            'qualification' => 'fsc',
            'academic_group' => 'pre_medical',
            'matric_percentage' => 86.0,
            'fsc_percentage' => 79.5,
            'desired_program' => 'computing',
            'preferred_location' => 'all',
            'test_type' => 'fast',
            'test_score' => 74,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Official Academic Eligibility Results');
        // Pre-medical students in computing must see the remedial math condition
        $response->assertSee('Conditional / Action Required');
        $response->assertSee('SRO 45(I)/2026', false);
    }

    /**
     * 6. Test Statutory Rules Explanation repository and detail views.
     */
    public function test_eligibility_rules_repository_and_detail_render(): void
    {
        // Index / default rule view
        $response = $this->get('/eligibility/rules');
        $response->assertStatus(200);
        $response->assertSee('Statutory Rules');
        $response->assertSee('Gazette Clauses');
        $response->assertSee('Statutory Rules Library');
        $response->assertSee('PEC Minimum 60% FSc Pre-Engineering Mandate');
        $response->assertSee('NCEAC Minimum 50% Intermediate Baseline Rule');
        $response->assertSee('HEC Pre-Medical to Computing Deficiency Mathematics Policy');

        // Specific rule inspection: PEC 60% rule
        $pecResponse = $this->get('/eligibility/rules/pec-eng-60');
        $pecResponse->assertStatus(200);
        $pecResponse->assertSee('PEC Minimum 60% FSc Pre-Engineering Mandate');
        $pecResponse->assertSee('Pakistan Engineering Council (PEC)');
        $pecResponse->assertSee('PEC Act No. V of 1976', false);

        // Specific rule inspection: NCEAC rule
        $nceacResponse = $this->get('/eligibility/rules/nceac-comp-50');
        $nceacResponse->assertStatus(200);
        $nceacResponse->assertSee('NCEAC Minimum 50% Intermediate Baseline Rule');
        $nceacResponse->assertSee('National Computing Education Accreditation Council (NCEAC)');

        // Specific rule inspection: Pre-Medical Computing transition
        $hecResponse = $this->get('/eligibility/rules/hec-premed-cs');
        $hecResponse->assertStatus(200);
        $hecResponse->assertSee('HEC Pre-Medical to Computing Deficiency Mathematics Policy');
        $hecResponse->assertSee('SRO 45(I)/2026', false);
    }

    /**
     * 7. Test Personalized University Shortlist renders and stores university entries.
     */
    public function test_personalized_shortlist_renders_and_adds_university(): void
    {
        // 1. Shortlist view
        $response = $this->get('/eligibility/shortlist');
        $response->assertStatus(200);
        $response->assertSee('Personalized University Shortlist');
        $response->assertSee('Curated Candidate Portfolio');
        $response->assertSee('National University of Sciences &amp; Technology', false);

        // 2. Add university to shortlist
        $addResponse = $this->post('/eligibility/shortlist', [
            'slug' => 'pieas',
            'name' => 'Pakistan Institute of Engineering and Applied Sciences',
            'acronym' => 'PIEAS',
            'city' => 'Islamabad',
            'fee' => 'PKR 145,000 / sem',
        ]);

        $addResponse->assertStatus(302);
        $addResponse->assertSessionHas('toast_success', 'University added to your personalized shortlist!');

        // 3. Shortlist view now reflects PIEAS
        $afterAdd = $this->get('/eligibility/shortlist');
        $afterAdd->assertStatus(200);
        $afterAdd->assertSee('Pakistan Institute of Engineering and Applied Sciences');
    }
}
