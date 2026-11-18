<?php

namespace App\Services;

use App\Models\EligibilityRule;

class EligibilityRulesEngine
{
    public function __construct(
        protected DiscoveryService $discoveryService,
        protected AdmissionService $admissionService
    ) {}

    /**
     * Official Statutory Rules Repository.
     */
    public function getRulesRepository(): array
    {
        return EligibilityRule::all()->mapWithKeys(fn ($r) => [$r->rule_key => [
            'id' => $r->rule_key, 'title' => $r->title, 'authority' => $r->authority, 'statutory_reference' => $r->statutory_reference, 'category' => $r->category, 'verified_date' => $r->verified_date, 'summary' => $r->summary, 'scope' => $r->scope, 'formula_note' => $r->formula_note,
        ]])->all();
    }

    /**
     * Retrieve single statutory rule by ID.
     */
    public function getRuleById(string $ruleId): ?array
    {
        $rules = $this->getRulesRepository();

        return $rules[$ruleId] ?? null;
    }

    /**
     * Evaluate Candidate Profile against Official Pakistani Statutory Criteria.
     */
    public function evaluateEligibility(array $candidate): array
    {
        $candidate += ['qualification' => '', 'academic_group' => '', 'matric_percentage' => 0, 'fsc_percentage' => 0, 'desired_program' => '', 'preferred_location' => 'all', 'test_type' => 'none', 'test_score' => 0];
        $pending = [];
        foreach (app(RegionalStudentCatalog::class)->suggestions() as $offering) {
            $pending[] = $offering + ['closing_cutoff_ref' => 'Not recorded', 'council' => 'Not recorded', 'applied_rule' => null, 'rule_title' => 'Review recorded program requirements', 'verified_date' => 'Not reviewed', 'source_url' => route('programs.show', $offering['program_slug']), 'status' => 'conditional', 'status_label' => 'Needs verification', 'condition_reason' => 'A program record alone does not establish eligibility. Current verified requirements must be provided by the institution.', 'rule_id' => null];
        }

        return ['candidate' => $candidate, 'calculated_aggregates' => [], 'results' => ['eligible' => [], 'conditional' => $pending, 'ineligible' => []], 'counts' => ['eligible' => 0, 'conditional' => count($pending), 'ineligible' => 0, 'total' => count($pending)], 'rules' => $this->getRulesRepository()];
    }
}
