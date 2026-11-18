<?php

namespace Tests\Feature;

use App\Models\EligibilityRule;
use App\Models\SavedItem;
use App\Models\University;
use App\Services\EligibilityRulesEngine;
use Tests\DatabaseFeatureTestCase;

class ComparisonAndEligibilityPhaseTest extends DatabaseFeatureTestCase
{
    public function test_comparison_requires_an_explicit_selection_and_uses_recorded_universities(): void
    {
        $this->get('/compare')->assertRedirect(route('compare.builder'));
        $this->get('/compare/builder')->assertOk()->assertSee('NUML');
        $this->get('/compare?unis=numl')->assertOk()->assertSee('NUML');
    }

    public function test_eligibility_does_not_invent_results_or_rules_when_none_are_recorded(): void
    {
        $this->get('/eligibility')->assertOk();
        $this->assertSame([], app(EligibilityRulesEngine::class)->getRulesRepository());
        $result = app(EligibilityRulesEngine::class)->evaluateEligibility(['fsc_percentage' => 90]);
        $this->assertSame(0, $result['counts']['eligible']);
        $this->assertSame([], $result['calculated_aggregates']);
    }

    public function test_rules_are_loaded_from_database_and_shortlist_writes_real_references(): void
    {
        EligibilityRule::create(['rule_key' => 'test-rule', 'title' => 'Recorded requirement', 'authority' => 'Institution', 'category' => 'Admission', 'summary' => 'A recorded requirement']);
        $this->assertSame('Recorded requirement', app(EligibilityRulesEngine::class)->getRulesRepository()['test-rule']['title']);
        $this->loginAs('student@gmail.com');
        $this->post('/eligibility/shortlist', ['slug' => 'numl'])->assertRedirect();
        $this->assertSame(University::where('slug', 'numl')->value('id'), SavedItem::firstOrFail()->item_id);
        $this->get('/eligibility/shortlist')->assertOk()->assertSee('NUML');
    }
}
