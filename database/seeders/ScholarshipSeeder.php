<?php

namespace Database\Seeders;

use App\Models\Province;
use App\Models\Scholarship;
use App\Models\University;
use App\Services\AdmissionService;
use App\Services\RegionCatalog;
use Illuminate\Database\Seeder;
use ReflectionClass;

class ScholarshipSeeder extends Seeder
{
    public function run(): void
    {
        $admissionService = new AdmissionService;
        $ref = new ReflectionClass($admissionService);

        $schProp = $ref->getProperty('scholarships');
        $schProp->setAccessible(true);
        $scholarships = $schProp->getValue($admissionService);

        $catalog = app(RegionCatalog::class);

        // Map scholarships to target provinces
        $provinceMap = [
            'hec-need-based' => ['punjab', 'ict'],
            'ehsaas-undergraduate' => ['punjab', 'ict'],
            'lums-nop' => ['punjab'],
            'peef-special-quota' => ['punjab'],
            'beef-excellence-scholarship' => ['balochistan'],
            'iba-sindh-talent-hunt' => ['sindh'],
            'nust-endowment-aid' => ['ict', 'punjab'],
            'fast-study-loan' => ['ict', 'punjab'],
        ];

        foreach ($scholarships as $s) {
            $uni = null;
            $campus = null;
            if (! empty($s['university_slug'])) {
                $uni = University::withoutGlobalScope('regional_catalog')->where('slug', $s['university_slug'])->first();
                if ($uni) {
                    $campus = $uni->campuses()->withoutGlobalScope('regional_catalog')
                        ->where(fn ($q) => $q->where('is_main_campus', true))->first()
                        ?? $uni->campuses()->withoutGlobalScope('regional_catalog')->first();
                }
            }

            $targetRegions = $provinceMap[$s['slug']] ?? ($s['target_provinces'] ?? ['punjab', 'ict']);
            $isAllowed = false;
            foreach ($targetRegions as $tr) {
                if ($catalog->allowsRegion($tr)) {
                    $isAllowed = true;
                    break;
                }
            }

            $scholarship = Scholarship::withoutGlobalScope('regional_catalog')->updateOrCreate(
                ['slug' => $s['slug']],
                [
                    'university_id' => $uni?->id,
                    'campus_id' => $campus?->id,
                    'coverage_status' => $isAllowed ? 'verified' : 'out_of_scope',
                    'title' => $s['title'],
                    'provider' => $s['provider'] ?? 'HEC',
                    'type' => $s['type'] ?? 'need_based',
                    'type_label' => $s['type_label'] ?? 'Need-Based Financial Assistance',
                    'category' => $s['category'] ?? ($s['type'] ?? 'need_based'),
                    'funding_type' => $s['funding_type'] ?? 'Full Tuition',
                    'coverage' => $s['coverage'] ?? $s['coverage_amount'] ?? null,
                    'coverage_amount' => $s['coverage_amount'] ?? null,
                    'deadline' => $s['deadline'] ?? null,
                    'deadline_date' => ! empty($s['deadline_date']) ? $s['deadline_date'] : null,
                    'beneficiaries' => $s['beneficiaries'] ?? null,
                    'applicable_universities' => $s['applicable_universities'] ?? null,
                    'universities_scope' => $s['universities_scope'] ?? null,
                    'target_provinces' => $targetRegions,
                    'degree_levels' => $s['degree_levels'] ?? ['bs'],
                    'gpa_requirement' => $s['gpa_requirement'] ?? null,
                    'income_ceiling' => $s['income_ceiling'] ?? null,
                    'description' => $s['description'] ?? null,
                    'eligibility_criteria' => $s['eligibility_criteria'] ?? [],
                    'benefits' => $s['benefits'] ?? [],
                    'selection_process' => $s['selection_process'] ?? [],
                    'application_process' => is_array($s['application_process'] ?? null) ? json_encode($s['application_process']) : ($s['application_process'] ?? null),
                    'required_documents' => $s['required_documents'] ?? [],
                    'official_url' => $s['official_url'] ?? null,
                    'source_citation' => $s['source_citation'] ?? null,
                    'verified_at' => $s['verified_at'] ?? '01 May 2026',
                    'status' => $isAllowed ? ($s['status'] ?? 'open') : 'inactive',
                    'featured' => $s['featured'] ?? false,
                ]
            );

            // Sync scholarship regions
            $provinceIds = [];
            foreach ($targetRegions as $trSlug) {
                $canonical = $catalog->normalizeRegion($trSlug) ?? $trSlug;
                $prov = Province::where('slug', $canonical)->orWhere('slug', $trSlug)->first();
                if ($prov) {
                    $provinceIds[] = $prov->id;
                }
            }
            if (! empty($provinceIds)) {
                $scholarship->regions()->sync($provinceIds);
            }
        }
    }
}
