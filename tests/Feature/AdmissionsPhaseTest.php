<?php

namespace Tests\Feature;

use Tests\TestCase;

class AdmissionsPhaseTest extends TestCase
{
    /**
     * Test Admission Explorer index and filtering.
     */
    public function test_admissions_explorer_renders_and_filters(): void
    {
        $response = $this->get('/admissions');
        $response->assertStatus(200);
        $response->assertSee('National University Admission Explorer');
        $response->assertSee('NUST Fall 2026');
        $response->assertSee('FAST-NUCES');

        // Filter by status
        $responseOpen = $this->get('/admissions?status=open');
        $responseOpen->assertStatus(200);
        $responseOpen->assertSee('Admissions Open');

        // Filter by province
        $responsePunjab = $this->get('/admissions?province=punjab');
        $responsePunjab->assertStatus(200);
        $responsePunjab->assertSee('LUMS');

        // Search query
        $responseSearch = $this->get('/admissions?q=NUST');
        $responseSearch->assertStatus(200);
        $responseSearch->assertSee('NUST Entry Test');

        // Empty state
        $responseEmpty = $this->get('/admissions?q=NonExistentIntakeXYZ999');
        $responseEmpty->assertStatus(200);
        $responseEmpty->assertSee('No admissions found');
    }

    /**
     * Test Admission Detail page for valid and invalid campaign slugs.
     */
    public function test_admission_detail_renders_and_handles_404(): void
    {
        // Valid admission campaign
        $response = $this->get('/admissions/nust-fall-2026');
        $response->assertStatus(200);
        $response->assertSee('NUST Fall 2026 Undergraduate Admissions');
        $response->assertSee('Official Admission Timeline');
        $response->assertSee('Eligibility Criteria');
        $response->assertSee('How to Apply Step-by-Step');

        // Invalid slug
        $response404 = $this->get('/admissions/non-existent-campaign-slug');
        $response404->assertStatus(404);
    }

    /**
     * Test Admission Deadline Calendar screen.
     */
    public function test_admission_calendar_renders_with_filters(): void
    {
        $response = $this->get('/admissions/calendar');
        $response->assertStatus(200);
        $response->assertSee('National Admission');
        $response->assertSee('Deadline Calendar');
        $response->assertSee('chronological events');

        // Filter by month (July 2026)
        $responseJuly = $this->get('/admissions/calendar?month=7');
        $responseJuly->assertStatus(200);
        $responseJuly->assertSee('July 2026');

        // Filter by category
        $responseDeadline = $this->get('/admissions/calendar?category=deadline');
        $responseDeadline->assertStatus(200);
        $responseDeadline->assertSee('Deadline');
    }

    /**
     * Test Admission Document Checklist screen.
     */
    public function test_admission_document_checklist_renders(): void
    {
        $response = $this->get('/admissions/checklist');
        $response->assertStatus(200);
        $response->assertSee('Document Checklist');
        $response->assertSee('Academic Transcripts');
        $response->assertSee('IBCC Equivalence Certificate');
        $response->assertSee('Official Pakistani Attestation Authorities');
    }

    /**
     * Test Scholarship Explorer index and filtering.
     */
    public function test_scholarship_explorer_renders_and_filters(): void
    {
        $response = $this->get('/scholarships');
        $response->assertStatus(200);
        $response->assertSee('National Scholarship');
        $response->assertSee('HEC Need-Based');
        $response->assertSee('Ehsaas Undergraduate');

        // Filter by type
        $responseNeed = $this->get('/scholarships?type=need_based');
        $responseNeed->assertStatus(200);
        $responseNeed->assertSee('Need-Based');

        // Search query
        $responseSearch = $this->get('/scholarships?q=Ehsaas');
        $responseSearch->assertStatus(200);
        $responseSearch->assertSee('Ehsaas Undergraduate Scholarship');

        // Empty state
        $responseEmpty = $this->get('/scholarships?q=UnmatchedScholarshipFund999');
        $responseEmpty->assertStatus(200);
        $responseEmpty->assertSee('No scholarships found');
    }

    /**
     * Test Scholarship Detail page for valid and invalid slugs.
     */
    public function test_scholarship_detail_renders_and_handles_404(): void
    {
        // Valid scholarship
        $response = $this->get('/scholarships/hec-need-based');
        $response->assertStatus(200);
        $response->assertSee('HEC Need-Based Undergraduate Scholarship');
        $response->assertSee('Entitlements');
        $response->assertSee('Eligibility Criteria');
        $response->assertSee('Required Supporting Documents');

        // Invalid slug
        $response404 = $this->get('/scholarships/invalid-scholarship-slug-999');
        $response404->assertStatus(404);
    }
}
