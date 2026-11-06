<?php

namespace Database\Seeders;

use App\Models\Admission;
use App\Models\University;
use App\Services\AdmissionService;
use App\Services\RegionCatalog;
use Illuminate\Database\Seeder;
use ReflectionClass;

class AdmissionSeeder extends Seeder
{
    public function run(): void
    {
        $admissionService = new AdmissionService;
        $ref = new ReflectionClass($admissionService);

        $admProp = $ref->getProperty('admissions');
        $admProp->setAccessible(true);
        $admissions = $admProp->getValue($admissionService);

        $catalog = app(RegionCatalog::class);

        foreach ($admissions as $a) {
            $uni = University::withoutGlobalScope('regional_catalog')->where('slug', $a['university_slug'])->first();
            if (! $uni) {
                continue;
            }

            // Find constituent campus or fall back to main campus
            $campus = $uni->campuses()->withoutGlobalScope('regional_catalog')
                ->where(function ($q) use ($a) {
                    $q->whereIn('name', $a['campuses'] ?? [])
                        ->orWhere('is_main_campus', true);
                })->first() ?? $uni->campuses()->withoutGlobalScope('regional_catalog')->first();

            $isAllowed = $catalog->allowsRegion($a['province'] ?? null) || ($campus && $catalog->allowsRegion($campus->province ?? null));

            Admission::withoutGlobalScope('regional_catalog')->updateOrCreate(
                ['slug' => $a['slug']],
                [
                    'university_id' => $uni->id,
                    'campus_id' => $campus?->id,
                    'coverage_status' => $isAllowed && $campus ? 'verified' : 'out_of_scope',
                    'title' => $a['title'],
                    'session' => $a['session'] ?? 'Fall 2026',
                    'status' => $isAllowed ? ($a['status'] ?? 'open') : 'inactive',
                    'degree_levels' => $a['degree_levels'] ?? ['bs'],
                    'disciplines' => $a['disciplines'] ?? ['computing'],
                    'campuses' => $campus ? [$campus->name] : ($a['campuses'] ?? []),
                    'registration_start' => $a['registration_start'] ?? null,
                    'deadline' => $a['deadline'] ?? null,
                    'deadline_date' => ! empty($a['deadline_date']) ? $a['deadline_date'] : null,
                    'test_dates' => $a['test_dates'] ?? null,
                    'merit_date' => $a['merit_date'] ?? null,
                    'classes_date' => $a['classes_date'] ?? null,
                    'entry_test_requirement' => $a['entry_test'] ?? null,
                    'application_fee' => $a['application_fee'] ?? null,
                    'official_url' => $a['official_url'] ?? null,
                    'source_citation' => $a['source_citation'] ?? null,
                    'verified_at' => $a['verified_at'] ?? 'May 2026',
                    'featured' => $a['featured'] ?? false,
                    'summary' => $a['summary'] ?? null,
                    'eligibility_summary' => $a['eligibility_summary'] ?? null,
                    'weightage_formula' => $a['weightage_formula'] ?? null,
                    'available_programs' => $a['available_programs'] ?? [],
                ]
            );
        }
    }
}
