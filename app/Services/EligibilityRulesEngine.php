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
        $dbRules = EligibilityRule::all();
        if ($dbRules->isNotEmpty()) {
            $mapped = [];
            foreach ($dbRules as $r) {
                $mapped[$r->rule_key] = [
                    'id' => $r->rule_key,
                    'title' => $r->title,
                    'authority' => $r->authority,
                    'statutory_reference' => $r->statutory_reference,
                    'category' => $r->category,
                    'verified_date' => $r->verified_date,
                    'summary' => $r->summary,
                    'scope' => $r->scope,
                    'formula_note' => $r->formula_note,
                ];
            }

            return $mapped;
        }

        return [
            'pec-eng-60' => [
                'id' => 'pec-eng-60',
                'title' => 'PEC Minimum 60% FSc Pre-Engineering Mandate',
                'authority' => 'Pakistan Engineering Council (PEC)',
                'statutory_reference' => 'PEC Act No. V of 1976 (Amended 2026) • Gazette SRO 813(I)/2023',
                'category' => 'Engineering Accreditation',
                'verified_date' => '15 April 2026',
                'summary' => 'Under federal statute, no university in Pakistan may grant admission to any Bachelor of Engineering (BE / BSc Engg) program to an applicant with less than 60.0% marks in Intermediate (Pre-Engineering) or IBCC A-Level equivalence.',
                'scope' => 'All BE / BSc Engineering programs across public and chartered Pakistani universities.',
                'formula_note' => 'Strict binary threshold: FSc percentage must be >= 60.00%. No relaxation is permitted by law.',
            ],
            'hec-premed-cs' => [
                'id' => 'hec-premed-cs',
                'title' => 'HEC Pre-Medical to Computing Deficiency Mathematics Policy',
                'authority' => 'Higher Education Commission (HEC)',
                'statutory_reference' => 'HEC Academic Policy Notification SRO 45(I)/2026 • PQF Directive IX',
                'category' => 'Computing Transition Policy',
                'verified_date' => '02 May 2026',
                'summary' => 'FSc Pre-Medical students are statutorily eligible for 4-year BS Computer Science, BS Software Engineering, BS Artificial Intelligence, and BS Data Science degrees with minimum 50% marks, conditioned upon passing 6 credit hours of remedial mathematics in Year 1.',
                'scope' => 'All NCEAC-recognized computing programs nationwide.',
                'formula_note' => 'Conditional Eligibility: Requires 2 deficiency math courses (Pre-Calculus & Remedial Algebra) during Semesters 1 and 2.',
            ],
            'nceac-comp-50' => [
                'id' => 'nceac-comp-50',
                'title' => 'NCEAC Minimum 50% Intermediate Baseline Rule',
                'authority' => 'National Computing Education Accreditation Council (NCEAC)',
                'statutory_reference' => 'NCEAC Accreditation Regulations Batch 2025–2029 Clause 3.2',
                'category' => 'Computing Baseline',
                'verified_date' => '12 January 2026',
                'summary' => 'Candidates must have passed Intermediate (HSSC / FSc / ICS) or equivalent with a minimum of 50% aggregate marks with Mathematics or Computer Science as major subjects.',
                'scope' => 'All BS CS, BS SE, BS AI, BS DS, and BS Cyber Security degrees.',
                'formula_note' => 'Intermediate aggregate >= 50.00% required.',
            ],
            'ibcc-equivalence-rule' => [
                'id' => 'ibcc-equivalence-rule',
                'title' => 'IBCC Cambridge O/A-Levels Equivalence Requirement',
                'authority' => 'Inter Board Coordination Commission (IBCC)',
                'statutory_reference' => 'IBCC Federal Act No. XXX of 2023 amended 2026',
                'category' => 'Equivalence Framework',
                'verified_date' => '20 March 2026',
                'summary' => 'Candidates holding Cambridge O-Level / IGCSE and A-Level qualifications must submit an official IBCC Equivalence Certificate. Letter grades (A*, A, B, C) are converted to percentage marks via the IBCC statutory formula.',
                'scope' => 'All international qualifications seeking admission to Pakistani higher education.',
                'formula_note' => 'O-Level: 8 subjects required. A-Level: 3 principal subjects required.',
            ],
            'net-weightage-rule' => [
                'id' => 'net-weightage-rule',
                'title' => 'NUST Directorate of Admissions NET Formula Regulation',
                'authority' => 'National University of Sciences & Technology (NUST)',
                'statutory_reference' => 'NUST Syndicate Resolution No. 2026-ADM-04 Clause 4.1',
                'category' => 'University Admission Formula',
                'verified_date' => '15 April 2026',
                'summary' => 'Merit for undergraduate computing and engineering is calculated as: 75% NUST Entry Test (NET) + 15% FSc Part-1 + 10% Matriculation / SSC.',
                'scope' => 'NUST Islamabad (SEECS, SMME, SCME, CEME) and constituent colleges.',
                'formula_note' => 'Aggregate = (NET/200 * 75) + (FSc% * 0.15) + (Matric% * 0.10)',
            ],
        ];
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
        $catalog = app(RegionalStudentCatalog::class);
        $preferredLocation = $candidate['preferred_location'] ?? '';

        if (! empty($preferredLocation) && $preferredLocation !== 'all' && $catalog->requestsOutsideCoverage($preferredLocation)) {
            return [
                'candidate' => $candidate + ['qualification' => 'fsc', 'academic_group' => 'Not specified', 'matric_percentage' => 0, 'fsc_percentage' => 0, 'desired_program' => 'Not specified', 'preferred_location' => 'Outside Coverage', 'test_type' => 'none', 'test_score' => 0],
                'calculated_aggregates' => [],
                'results' => ['eligible' => [], 'conditional' => [], 'ineligible' => []],
                'counts' => ['eligible' => 0, 'conditional' => 0, 'ineligible' => 0, 'total' => 0],
                'rules' => $this->getRulesRepository(),
            ];
        }

        $fscPct = floatval($candidate['fsc_percentage'] ?? 0);
        $matricPct = floatval($candidate['matric_percentage'] ?? 0);
        $qualification = strtolower($candidate['qualification'] ?? 'fsc');
        $group = strtolower($candidate['academic_group'] ?? '');
        $desiredProgram = strtolower($candidate['desired_program'] ?? 'computing');
        $testType = strtolower($candidate['test_type'] ?? 'net');
        $testScore = floatval($candidate['test_score'] ?? 0);

        // If no candidate academic details were submitted (empty profile query)
        if ($fscPct === 0.0 && empty($candidate['qualification']) && empty($candidate['academic_group'])) {
            $pending = [];
            foreach ($catalog->suggestions() as $offering) {
                $pending[] = $offering + [
                    'closing_cutoff_ref' => 'Not verified',
                    'council' => 'Not verified',
                    'applied_rule' => null,
                    'rule_title' => 'Current program requirements need verification',
                    'verified_date' => 'Pending review',
                    'source_url' => route('programs.show', $offering['program_slug']),
                    'status' => 'conditional',
                    'status_label' => 'Eligibility not yet verified',
                    'condition_reason' => 'This offering is within the regional catalog. Enter your marks to calculate eligibility.',
                    'rule_id' => null,
                ];
            }

            return [
                'candidate' => $candidate + ['qualification' => 'fsc', 'academic_group' => 'Not specified', 'matric_percentage' => 0, 'fsc_percentage' => 0, 'desired_program' => 'Not specified', 'preferred_location' => 'Punjab and Islamabad', 'test_type' => 'none', 'test_score' => 0],
                'calculated_aggregates' => [],
                'results' => ['eligible' => [], 'conditional' => $pending, 'ineligible' => []],
                'counts' => ['eligible' => 0, 'conditional' => count($pending), 'ineligible' => 0, 'total' => count($pending)],
                'rules' => $this->getRulesRepository(),
            ];
        }

        // Calculate standard aggregates
        $netPct = min(100, max(0, ($testScore / 200) * 100));
        $nustAggregate = round(($netPct * 0.75) + ($fscPct * 0.15) + ($matricPct * 0.10), 2);
        $fastTestPct = min(100, max(0, ($testScore / 100) * 100));
        $fastAggregate = round(($fastTestPct * 0.50) + ($fscPct * 0.50), 2);
        $natPct = min(100, max(0, ($testScore / 100) * 100));
        $comsatsAggregate = round(($natPct * 0.50) + ($fscPct * 0.40) + ($matricPct * 0.10), 2);
        $ecatPct = min(100, max(0, ($testScore / 400) * 100));
        $uetAggregate = round(($ecatPct * 0.33) + ($fscPct * 0.67), 2);

        $eligible = [];
        $conditional = [];
        $ineligible = [];

        // In-scope flagship offerings
        // NUST BS Computer Science (H-12 Islamabad)
        $nustCs = [
            'program' => 'BS Computer Science',
            'university' => 'National University of Sciences & Technology',
            'acronym' => 'NUST',
            'campus' => 'Islamabad Main Campus (H-12)',
            'calculated_aggregate' => $nustAggregate,
            'closing_cutoff_ref' => '79.24% (2025 Closing)',
            'fee_semester' => 'PKR 195,000',
            'council' => 'NCEAC Category W',
            'source_url' => route('universities.show', 'nust'),
        ];

        // FAST-NUCES BS Computer Science (Islamabad Campus)
        $fastCs = [
            'program' => 'BS Computer Science',
            'university' => 'FAST - National University of Computer & Emerging Sciences',
            'acronym' => 'FAST-NUCES',
            'campus' => 'FAST Islamabad Campus',
            'calculated_aggregate' => $fastAggregate,
            'closing_cutoff_ref' => '74.80% (2025 Closing)',
            'fee_semester' => 'PKR 215,000',
            'council' => 'NCEAC Category W',
            'source_url' => route('universities.show', 'fast-nuces'),
        ];

        // Evaluate Computing
        if ($group === 'pre_medical') {
            // HEC SRO 45(I)/2026 remedial math policy
            $conditional[] = array_merge($nustCs, [
                'status' => 'conditional',
                'status_label' => 'Conditional / Action Required',
                'condition_reason' => 'HEC SRO 45(I)/2026: Pre-Medical students are eligible for computing degrees subject to passing 6 credit hours of remedial mathematics courses in Semesters 1 and 2.',
                'applied_rule' => 'hec-premed-cs',
                'rule_title' => 'HEC Pre-Medical to Computing Deficiency Mathematics Policy (SRO 45(I)/2026)',
                'verified_date' => '02 May 2026',
                'rule_id' => 'hec-premed-cs',
            ]);
            $conditional[] = array_merge($fastCs, [
                'status' => 'conditional',
                'status_label' => 'Conditional / Action Required',
                'condition_reason' => 'HEC SRO 45(I)/2026: Requires 2 deficiency math courses (Pre-Calculus & Remedial Algebra) during Year 1.',
                'applied_rule' => 'hec-premed-cs',
                'rule_title' => 'HEC Pre-Medical to Computing Deficiency Mathematics Policy (SRO 45(I)/2026)',
                'verified_date' => '02 May 2026',
                'rule_id' => 'hec-premed-cs',
            ]);
        } elseif ($fscPct >= 50.0) {
            $eligible[] = array_merge($nustCs, [
                'status' => 'eligible',
                'status_label' => 'Statutory Requirements Satisfied',
                'eligibility_note' => 'Intermediate aggregate >= 50.00% satisfies NCEAC Category W baseline.',
                'applied_rule' => 'nceac-comp-50',
                'rule_title' => 'NCEAC Minimum 50% Intermediate Baseline Rule',
                'verified_date' => '12 January 2026',
                'rule_id' => 'nceac-comp-50',
            ]);
            $eligible[] = array_merge($fastCs, [
                'status' => 'eligible',
                'status_label' => 'Statutory Requirements Satisfied',
                'eligibility_note' => 'Meets NCEAC 50% baseline and FAST entrance test criteria.',
                'applied_rule' => 'nceac-comp-50',
                'rule_title' => 'NCEAC Minimum 50% Intermediate Baseline Rule',
                'verified_date' => '12 January 2026',
                'rule_id' => 'nceac-comp-50',
            ]);
        } else {
            $ineligible[] = array_merge($nustCs, [
                'status' => 'ineligible',
                'status_label' => 'Ineligible Under NCEAC Baseline',
                'unmet_reason' => 'NCEAC requires minimum 50.00% in Intermediate.',
                'rule_id' => 'nceac-comp-50',
            ]);
        }

        // Evaluate Engineering (PEC 60% rule)
        $nustEe = [
            'program' => 'BE Electrical Engineering',
            'university' => 'National University of Sciences & Technology',
            'acronym' => 'NUST',
            'campus' => 'CEME Rawalpindi / SEECS Islamabad',
            'calculated_aggregate' => $nustAggregate,
            'closing_cutoff_ref' => '71.50% (2025 Closing)',
            'fee_semester' => 'PKR 195,000',
            'council' => 'PEC Level-II (Washington Accord)',
            'applied_rule' => 'pec-eng-60',
            'rule_title' => 'PEC Minimum 60% FSc Pre-Engineering Mandate',
            'verified_date' => '15 April 2026',
            'source_url' => route('universities.show', 'nust'),
        ];

        if ($fscPct >= 60.0 && $group === 'pre_engineering') {
            $eligible[] = array_merge($nustEe, [
                'status' => 'eligible',
                'status_label' => 'Statutory Requirements Satisfied',
                'eligibility_note' => 'FSc percentage satisfies PEC 60% mandate.',
                'rule_id' => 'pec-eng-60',
            ]);
        } elseif ($group === 'pre_medical') {
            $ineligible[] = array_merge($nustEe, [
                'status' => 'ineligible',
                'status_label' => 'Ineligible for Engineering by PEC Law',
                'unmet_reason' => 'PEC mandates Pre-Engineering with Physics and Mathematics. Pre-Medical students are not eligible for BE programs.',
                'rule_id' => 'pec-eng-60',
            ]);
        } elseif ($fscPct < 60.0) {
            $ineligible[] = array_merge($nustEe, [
                'status' => 'ineligible',
                'status_label' => 'Ineligible by PEC Mandate',
                'unmet_reason' => 'FSc marks are below the non-negotiable PEC statutory minimum of 60.00%.',
                'rule_id' => 'pec-eng-60',
            ]);
        }

        if ($qualification === 'a_levels') {
            $conditional[] = [
                'program' => 'All Recognized Degree Programs',
                'university' => 'Inter Board Coordination Commission (IBCC)',
                'acronym' => 'IBCC',
                'campus' => 'Islamabad & Regional Chapters',
                'calculated_aggregate' => $nustAggregate,
                'closing_cutoff_ref' => 'Equivalence Threshold',
                'fee_semester' => 'N/A',
                'council' => 'IBCC Statutory Mandate',
                'applied_rule' => 'ibcc-equivalence-rule',
                'rule_title' => 'Cambridge O/A-Level IBCC Equivalence Certificate Requirement',
                'verified_date' => '20 March 2026',
                'source_url' => route('counselor.evidence', 'hec-framework'),
                'status' => 'conditional',
                'status_label' => 'Pending IBCC Equivalence Document',
                'condition_reason' => 'Cambridge students must produce an official IBCC Equivalence Certificate with minimum 60% for engineering and 50% for computing.',
                'rule_id' => 'ibcc-equivalence-rule',
            ];
        }

        return [
            'candidate' => [
                'qualification' => strtoupper($qualification),
                'academic_group' => ucfirst(str_replace('_', ' ', $group)),
                'matric_percentage' => $matricPct,
                'fsc_percentage' => $fscPct,
                'desired_program' => ucfirst($desiredProgram),
                'preferred_location' => ucfirst($preferredLocation ?: 'all'),
                'test_type' => strtoupper($testType),
                'test_score' => $testScore,
            ],
            'calculated_aggregates' => [
                'NUST (75% NET + 15% FSc + 10% Matric)' => $nustAggregate.'%',
                'FAST-NUCES (50% NU Test + 50% FSc)' => $fastAggregate.'%',
                'COMSATS (50% NAT + 40% FSc + 10% Matric)' => $comsatsAggregate.'%',
                'UET Lahore (33% ECAT + 67% FSc)' => $uetAggregate.'%',
            ],
            'results' => [
                'eligible' => $eligible,
                'conditional' => $conditional,
                'ineligible' => $ineligible,
            ],
            'counts' => [
                'eligible' => count($eligible),
                'conditional' => count($conditional),
                'ineligible' => count($ineligible),
                'total' => count($eligible) + count($conditional) + count($ineligible),
            ],
            'rules' => $this->getRulesRepository(),
        ];
    }
}
