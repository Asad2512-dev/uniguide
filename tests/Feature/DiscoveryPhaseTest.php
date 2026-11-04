<?php

namespace Tests\Feature;

use Tests\TestCase;

class DiscoveryPhaseTest extends TestCase
{
    /**
     * Test National University Directory index.
     */
    public function test_university_directory_renders_successfully(): void
    {
        $response = $this->get('/universities');

        $response->assertStatus(200);
        $response->assertSee('National University Directory');
        $response->assertSee('NUST');
        $response->assertSee('FAST');
        $response->assertSee('LUMS');
    }

    /**
     * Test University Directory filtering by province and sector.
     */
    public function test_university_directory_filters_work(): void
    {
        // Filter by province
        $responsePunjab = $this->get('/universities?province=punjab');
        $responsePunjab->assertStatus(200);
        $responsePunjab->assertSee('LUMS');

        // Filter by search query
        $responseSearch = $this->get('/universities?q=NUST');
        $responseSearch->assertStatus(200);
        $responseSearch->assertSee('National University of Sciences');

        // Filter by sector
        $responseSector = $this->get('/universities?sector=public');
        $responseSector->assertStatus(200);
        $responseSector->assertSee('Public Sector');

        // Empty state on zero match
        $responseEmpty = $this->get('/universities?q=NonExistentInstitutionXYZ');
        $responseEmpty->assertStatus(200);
        $responseEmpty->assertSee('No universities found');
    }

    /**
     * Test University Directory compact list view toggle.
     */
    public function test_university_directory_list_view_renders(): void
    {
        $response = $this->get('/universities?view=list');

        $response->assertStatus(200);
        $response->assertSee('View Profile');
    }

    /**
     * Test University Profile page for valid and invalid slugs.
     */
    public function test_university_profile_renders_and_handles_404(): void
    {
        // Valid university
        $response = $this->get('/universities/nust');
        $response->assertStatus(200);
        $response->assertSee('National University of Sciences');
        $response->assertSee('H-12');
        $response->assertSee('Category W');
        $response->assertSee('Act of Parliament No. XX of 1991');

        // Invalid university
        $response404 = $this->get('/universities/non-existent-university-slug');
        $response404->assertStatus(404);
    }

    /**
     * Test Campus Detail page for valid and invalid campus slugs.
     */
    public function test_campus_detail_renders_and_handles_404(): void
    {
        // Valid campus
        $response = $this->get('/universities/nust/campuses/islamabad-h12');
        $response->assertStatus(200);
        $response->assertSee('Islamabad Main Campus (H-12)');
        $response->assertSee('National Science');
        $response->assertSee('Student Accommodation');
        $response->assertSee('Conveyance & Transport', false);

        // Invalid campus slug under valid university
        $response404 = $this->get('/universities/nust/campuses/invalid-campus-xyz');
        $response404->assertStatus(404);
    }

    /**
     * Test Program Explorer index screen and filtering.
     */
    public function test_program_explorer_renders_and_filters(): void
    {
        $response = $this->get('/programs');
        $response->assertStatus(200);
        $response->assertSee('Academic Degree Program Explorer');
        $response->assertSee('BS Software Engineering');

        // Filter by discipline
        $responseComputing = $this->get('/programs?discipline=computing');
        $responseComputing->assertStatus(200);
        $responseComputing->assertSee('BS Computer Science');

        // Filter by university
        $responseNust = $this->get('/programs?university=nust');
        $responseNust->assertStatus(200);
        $responseNust->assertSee('NUST');

        // Empty state on zero match
        $responseEmpty = $this->get('/programs?q=QuantumTeleportationDegreeXYZ');
        $responseEmpty->assertStatus(200);
        $responseEmpty->assertSee('No degree programs found');
    }

    /**
     * Test Program Detail page for valid and invalid slugs.
     */
    public function test_program_detail_renders_and_handles_404(): void
    {
        // Valid program
        $response = $this->get('/programs/nust-bs-software-engineering');
        $response->assertStatus(200);
        $response->assertSee('BS Software Engineering');
        $response->assertSee('National University of Sciences');
        $response->assertSee('Admission Requirements');
        $response->assertSee('Curriculum Highlights');

        // Invalid program
        $response404 = $this->get('/programs/non-existent-program-xyz');
        $response404->assertStatus(404);
    }

    /**
     * Test Search Results page.
     */
    public function test_search_results_page_renders(): void
    {
        // Blank search page prompt
        $responseBlank = $this->get('/search');
        $responseBlank->assertStatus(200);
        $responseBlank->assertSee('Search Universities');

        // Search with query
        $responseSearch = $this->get('/search?q=NUST');
        $responseSearch->assertStatus(200);
        $responseSearch->assertSee('Search results for');
        $responseSearch->assertSee('NUST');

        // Unmatched query
        $responseNoMatch = $this->get('/search?q=NonExistentQueryXYZ123');
        $responseNoMatch->assertStatus(200);
        $responseNoMatch->assertSee('No results found');
    }

    /**
     * Test search suggestions JSON endpoint.
     */
    public function test_search_suggestions_api(): void
    {
        $response = $this->getJson('/api/search/suggestions?q=NUST');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'universities',
            'programs',
        ]);
    }
}
