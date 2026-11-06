<?php

namespace App\Services;

class ComparisonService
{
    public function __construct(
        protected DiscoveryService $discoveryService,
        protected AdmissionService $admissionService
    ) {}

    /**
     * Retrieve curated comparison presets for quick evaluation.
     */
    public function getPresetComparisons(): array
    {
        $presets = [
            [
                'id' => 'computing-leaders',
                'title' => 'National Computing Big-4 Battle',
                'description' => 'Direct comparison of Pakistan\'s top software engineering and CS powerhouses.',
                'slugs' => ['nust', 'fast-nuces', 'lums', 'uet-lahore'],
                'badge' => 'Most Popular',
                'badge_color' => 'brand',
                'icon' => 'laptop',
            ],
            [
                'id' => 'premier-engineering',
                'title' => 'Top Engineering & Technology Charters',
                'description' => 'Washington Accord Level-II accredited institutions with world-class faculty ratios.',
                'slugs' => ['nust', 'uet-lahore', 'fast-nuces'],
                'badge' => 'PEC Level-II',
                'badge_color' => 'success',
                'icon' => 'cpu',
            ],
            [
                'id' => 'business-titans',
                'title' => 'Premier Business & Management Schools',
                'description' => 'Rigorous business curriculum, international exchange, and placement networks.',
                'slugs' => ['lums', 'nust', 'quaid-i-azam-university'],
                'badge' => 'High Return',
                'badge_color' => 'indigo',
                'icon' => 'bar-chart-3',
            ],
            [
                'id' => 'healthcare-biosciences',
                'title' => 'Medical & Allied Biosciences Benchmark',
                'description' => 'World-ranked clinical teaching facilities and biomedical research hubs.',
                'slugs' => ['quaid-i-azam-university', 'nust'],
                'badge' => 'Clinical Excellence',
                'badge_color' => 'warning',
                'icon' => 'heart-pulse',
            ],
        ];
        $allowed = array_column($this->getSelectableUniversities(), 'slug');

        return collect($presets)->map(function (array $preset) use ($allowed): array {
            $preset['slugs'] = array_values(array_intersect($preset['slugs'], $allowed));
            $preset['description'] = 'Compare recorded institutions serving Punjab and Islamabad.';

            return $preset;
        })->filter(fn (array $preset): bool => count($preset['slugs']) >= 2)->values()->all();
    }

    /**
     * Retrieve available universities for the comparison selector.
     */
    public function getSelectableUniversities(): array
    {
        $all = $this->discoveryService->getUniversities([], 'name_asc', 500)['data'] ?? [];

        return array_map(fn ($u) => [
            'slug' => $u['slug'],
            'name' => $u['name'],
            'acronym' => $u['acronym'] ?? $u['name'],
            'city' => $u['city'] ?? 'Pakistan',
            'province' => $u['province_label'] ?? 'Pakistan',
            'sector' => ucfirst($u['sector'] ?? 'Public'),
            'ranking' => $u['hec_ranking'] ?? 'HEC Recognized',
        ], $all);
    }

    /**
     * Generate comprehensive comparison payload for up to 4 universities.
     */
    public function compare(array $slugs): array
    {
        // Enforce maximum 4 universities and remove duplicates
        $slugs = array_values(array_unique(array_slice($slugs, 0, 4)));

        if (empty($slugs)) {
            $slugs = ['nust', 'fast-nuces']; // Default 2 options
        }

        $universities = [];
        foreach ($slugs as $slug) {
            $uni = $this->discoveryService->getUniversityBySlug($slug);
            if ($uni) {
                $universities[] = $this->enrichUniversityForComparison($uni);
            }
        }

        abort_if(empty($universities), 404, 'No universities in the selection are within the active regional catalog.');

        // Build comparison metric sections
        $sections = [
            'overview' => [
                'title' => 'Institutional Identity & Standing',
                'icon' => 'building-2',
                'metrics' => [
                    'Charter Authority' => array_column($universities, 'charter_type'),
                    'Establishment Year' => array_column($universities, 'established'),
                    'HEC Category' => array_column($universities, 'hec_category'),
                    'National Rank' => array_column($universities, 'hec_ranking'),
                    'Acceptance Rate' => array_column($universities, 'acceptance_rate'),
                ],
            ],
            'academics' => [
                'title' => 'Academic Rigor & Faculty',
                'icon' => 'graduation-cap',
                'metrics' => [
                    'Total Student Body' => array_column($universities, 'total_students'),
                    'PhD Faculty Ratio' => array_column($universities, 'faculty_phd_ratio'),
                    'Campus Master Plan' => array_column($universities, 'campus_size'),
                    'Primary Accreditations' => array_column($universities, 'accreditation_summary'),
                ],
            ],
            'financials' => [
                'title' => 'Verified Tuition & Fees',
                'icon' => 'coins',
                'metrics' => [
                    'Semester Tuition Range' => array_column($universities, 'fee_range'),
                    'Admission One-Time Fee' => array_column($universities, 'admission_fee_str'),
                    'Refundable Security Deposit' => array_column($universities, 'security_deposit_str'),
                    'Hostel Charges (Per Semester)' => array_column($universities, 'hostel_charges_str'),
                ],
            ],
            'admissions' => [
                'title' => 'Admission Policy & Entry Tests',
                'icon' => 'calendar-check',
                'metrics' => [
                    'Mandatory Entry Tests' => array_column($universities, 'entry_test_accepted'),
                    'Merit Weightage Formula' => array_column($universities, 'merit_formula'),
                    'Typical CS / Engg Cutoff' => array_column($universities, 'cutoff_indicator'),
                    'Application Deadline' => array_column($universities, 'deadline_str'),
                    'Intake Cycle' => array_column($universities, 'status_str'),
                ],
            ],
            'scholarships' => [
                'title' => 'Financial Aid & Endowment Support',
                'icon' => 'award',
                'metrics' => [
                    'HEC Need-Based Scheme' => array_column($universities, 'hec_need_based_available'),
                    'Ehsaas / BISP Subsidies' => array_column($universities, 'ehsaas_available'),
                    'Institutional Merit Concessions' => array_column($universities, 'merit_waiver_policy'),
                    'External Endowments (PEEF/BEEF)' => array_column($universities, 'provincial_endowment_policy'),
                ],
            ],
            'facilities' => [
                'title' => 'Campus Life, Hostels & Location',
                'icon' => 'map-pin',
                'metrics' => [
                    'Primary Campus City' => array_column($universities, 'city_region'),
                    'Hostel Facility Guarantee' => array_column($universities, 'hostel_availability'),
                    'Sports & Olympiad Facilities' => array_column($universities, 'sports_facilities'),
                    'Regional Sub-Campuses' => array_column($universities, 'sub_campuses_count'),
                ],
            ],
            'statutory' => [
                'title' => 'Official Statutory Reference & Audit',
                'icon' => 'shield-check',
                'metrics' => [
                    'Parliamentary Act Reference' => array_column($universities, 'charter_citation'),
                    'Official Gazette Number' => array_column($universities, 'gazette_reference'),
                    'Last Verified Audit Date' => array_column($universities, 'verified_date'),
                ],
            ],
        ];

        return [
            'universities' => $universities,
            'sections' => $sections,
            'slugs' => $slugs,
            'count' => count($universities),
            'available_universities' => $this->getSelectableUniversities(),
            'presets' => $this->getPresetComparisons(),
        ];
    }

    /**
     * Enrich university array with formatted fields for comparison display.
     */
    protected function enrichUniversityForComparison(array $uni): array
    {
        return $uni + [
            'admission_fee_str' => 'Not verified', 'security_deposit_str' => 'Not verified',
            'hostel_charges_str' => 'Not verified', 'sports_facilities' => 'Not verified',
            'sub_campuses_count' => count($uni['campuses'] ?? []), 'accreditation_summary' => 'Not verified',
            'scholarship_summary' => 'Not verified',
            'entry_test_accepted' => 'Not verified', 'merit_formula' => 'Not verified',
            'cutoff_indicator' => 'Not verified', 'hec_need_based_available' => 'Not verified',
            'ehsaas_available' => 'Not verified', 'merit_waiver_policy' => 'Not verified',
            'provincial_endowment_policy' => 'Not verified',
            'city_region' => $uni['city'].' ('.$uni['province_label'].')',
            'deadline_str' => 'Not verified', 'status_str' => 'Not verified',
            'verified_date' => $uni['last_verified_at'] ?? 'Pending review',
        ];
    }
}
