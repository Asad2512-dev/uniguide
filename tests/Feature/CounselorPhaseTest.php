<?php

namespace Tests\Feature;

use App\Models\SystemSetting;
use App\Models\University;
use App\Services\CounselorService;
use App\Services\CounselorWorkspace;
use Tests\DatabaseFeatureTestCase;

class CounselorPhaseTest extends DatabaseFeatureTestCase
{
    public function test_counselor_pages_render_without_mock_conversations(): void
    {
        foreach (['/counselor', '/counselor/chat', '/counselor/guided', '/counselor/history', '/counselor/evidence', '/counselor/search'] as $path) {
            $this->get($path)->assertOk();
        }
        $this->assertSame([], app(CounselorWorkspace::class)->history());
    }

    public function test_recommendations_only_calculate_with_saved_institution_formulas_and_supplied_scores(): void
    {
        $u = University::where('slug', 'numl')->firstOrFail();
        $service = app(CounselorService::class);
        $this->assertSame([], $service->evaluateStudent([])['calculated_aggregates']);
        SystemSetting::set('university.'.$u->id.'.merit_formula', ['net_test_weight' => 50, 'fsc_hssc_weight' => 40, 'matric_ssc_weight' => 10]);
        $result = $service->evaluateStudent(['test_score' => 80, 'test_max' => 100, 'fsc_percentage' => 90, 'matric_percentage' => 70]);
        $this->assertSame(83.0, $result['calculated_aggregates']['numl']['score']);
        $this->assertSame(['reach' => [], 'match' => [], 'safety' => []], $result['tiers']);
    }

    public function test_conversational_search_uses_database_records_without_invented_relevance_scores(): void
    {
        $r = app(CounselorService::class)->searchConversational('NUML');
        $this->assertNotEmpty($r['matches']);
        $this->assertNull($r['matches'][0]['relevance_score']);
    }
}
