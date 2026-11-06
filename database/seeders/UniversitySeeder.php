<?php

namespace Database\Seeders;

use App\Models\Campus;
use App\Models\City;
use App\Models\Department;
use App\Models\Program;
use App\Models\Province;
use App\Models\University;
use App\Services\DiscoveryService;
use App\Services\RegionCatalog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use ReflectionClass;

class UniversitySeeder extends Seeder
{
    public function run(): void
    {
        $discovery = new DiscoveryService;
        $ref = new ReflectionClass($discovery);

        $uniProp = $ref->getProperty('universities');
        $uniProp->setAccessible(true);
        $universities = $uniProp->getValue($discovery);

        $progProp = $ref->getProperty('programs');
        $progProp->setAccessible(true);
        $programs = $progProp->getValue($discovery);

        $uniModelMap = [];
        $campusModelMap = [];

        foreach ($universities as $u) {
            if (! app(RegionCatalog::class)->allowsRegion($u['province'] ?? null)) {
                continue;
            }
            $region = Province::firstOrCreate(['slug' => app(RegionCatalog::class)->normalizeRegion($u['province'])], ['name' => $u['province_label'] ?? $u['province']]);
            $city = City::firstOrCreate(['province_id' => $region->id, 'slug' => Str::slug($u['city'])], ['name' => $u['city']]);
            $uni = University::withoutGlobalScope('regional_catalog')->updateOrCreate(
                ['slug' => $u['slug']],
                [
                    'name' => $u['name'],
                    'city_id' => $city->id,
                    'acronym' => $u['acronym'] ?? null,
                    'charter_type' => $u['charter_type'] ?? 'Chartered Institution',
                    'sector' => $u['sector'] ?? 'public',
                    'hec_ranking' => $u['hec_ranking'] ?? null,
                    'hec_category' => $u['hec_category'] ?? 'Category W',
                    'established' => $u['established'] ?? null,
                    'charter_citation' => $u['charter_citation'] ?? null,
                    'gazette_reference' => $u['gazette_reference'] ?? null,
                    'website' => $u['website'] ?? null,
                    'phone' => $u['phone'] ?? null,
                    'email' => $u['email'] ?? null,
                    'address' => $u['address'] ?? null,
                    'province' => $u['province'] ?? 'ict',
                    'province_label' => $u['province_label'] ?? ucfirst($u['province'] ?? 'ict'),
                    'city' => $u['city'] ?? 'Islamabad',
                    'fee_range' => $u['fee_range'] ?? null,
                    'fee_min' => $u['fee_min'] ?? 0,
                    'fee_max' => $u['fee_max'] ?? 0,
                    'acceptance_rate' => $u['acceptance_rate'] ?? null,
                    'total_students' => $u['total_students'] ?? null,
                    'faculty_phd_ratio' => $u['faculty_phd_ratio'] ?? null,
                    'campus_size' => $u['campus_size'] ?? null,
                    'hostel_availability' => $u['hostel_availability'] ?? null,
                    'status' => $u['status'] ?? 'open',
                    'deadline' => $u['deadline'] ?? null,
                    'featured' => $u['featured'] ?? false,
                    'accreditations' => $u['accreditations'] ?? [],
                    'description' => $u['description'] ?? null,
                ]
            );

            $uniModelMap[$u['slug']] = $uni;

            // Seed constituent campuses
            if (! empty($u['campuses'])) {
                foreach ($u['campuses'] as $c) {
                    if (! app(RegionCatalog::class)->allowsRegion($c['province'] ?? null) || empty($c['city'])) {
                        continue;
                    }
                    $campusRegion = Province::firstOrCreate(['slug' => app(RegionCatalog::class)->normalizeRegion($c['province'])], ['name' => $c['province']]);
                    $campusCity = City::firstOrCreate(['province_id' => $campusRegion->id, 'slug' => Str::slug($c['city'])], ['name' => $c['city']]);
                    $campus = Campus::withoutGlobalScope('regional_catalog')->updateOrCreate(
                        [
                            'university_id' => $uni->id,
                            'slug' => $c['slug'],
                        ],
                        [
                            'name' => $c['name'],
                            'city_id' => $campusCity->id,
                            'city' => $c['city'] ?? $uni->city,
                            'province' => $c['province'] ?? $uni->province,
                            'address' => $c['address'] ?? $uni->address,
                            'is_main_campus' => $c['is_main_campus'] ?? false,
                            'established' => $c['established'] ?? $uni->established,
                            'phone' => $c['phone'] ?? $uni->phone,
                            'email' => $c['email'] ?? $uni->email,
                            'status' => $c['status'] ?? 'active',
                            'verification_status' => 'verified',
                            'operational_status' => 'officially_listed',
                            'recognition_status' => 'hec_listed',
                            'facilities' => $c['facilities'] ?? [],
                            'description' => $c['description'] ?? null,
                        ]
                    );

                    $campusModelMap[$u['slug'].':'.$c['slug']] = $campus;
                }
            }

            // Seed faculties & departments for NUST as flagship model
            if ($u['slug'] === 'nust') {
                $depts = [
                    ['name' => 'School of Electrical Engineering & Computer Science (SEECS)', 'faculty_name' => 'Faculty of Computing & Information Technology', 'head_of_department' => 'Dr. Muhammad Bilal', 'dean_name' => 'Dr. Rafia Mumtaz'],
                    ['name' => 'School of Mechanical & Manufacturing Engineering (SMME)', 'faculty_name' => 'Faculty of Engineering', 'head_of_department' => 'Dr. Shahid Ikramullah', 'dean_name' => 'Dr. A. Waheed'],
                    ['name' => 'NUST Business School (NBS)', 'faculty_name' => 'Faculty of Management Sciences', 'head_of_department' => 'Dr. Nadia Tahir', 'dean_name' => 'Dr. R. Sheikh'],
                ];

                foreach ($depts as $d) {
                    Department::updateOrCreate(
                        [
                            'university_id' => $uni->id,
                            'name' => $d['name'],
                        ],
                        [
                            'faculty_name' => $d['faculty_name'],
                            'head_of_department' => $d['head_of_department'],
                            'dean_name' => $d['dean_name'],
                            'email' => 'contact@seecs.nust.edu.pk',
                            'status' => 'active',
                        ]
                    );
                }
            }
        }

        // Seed Programs
        foreach ($programs as $p) {
            $uni = $uniModelMap[$p['university_slug']] ?? University::where('slug', $p['university_slug'])->first();
            if (! $uni) {
                continue;
            }

            $campus = null;
            if (! empty($p['available_campuses'][0])) {
                $campus = $campusModelMap[$p['university_slug'].':'.$p['available_campuses'][0]] ?? null;
            }

            if (! $campus) {
                continue;
            }

            Program::withoutGlobalScope('regional_catalog')->updateOrCreate(
                [
                    'university_id' => $uni->id,
                    'slug' => $p['slug'],
                ],
                [
                    'campus_id' => $campus?->id,
                    'name' => $p['title'] ?? ($p['name'] ?? 'Degree Program'),
                    'degree_level' => $p['degree_level'] ?? 'undergraduate',
                    'degree_title' => $p['degree_level_label'] ?? ($p['degree_title'] ?? 'Bachelor of Science'),
                    'discipline' => $p['discipline'] ?? 'computing',
                    'duration_years' => $p['duration_years'] ?? 4,
                    'total_semesters' => $p['total_semesters'] ?? 8,
                    'credit_hours' => $p['credit_hours'] ?? 130,
                    'fee_per_semester' => $p['fee_per_semester'] ?? ($p['fee_semester'] ?? 'PKR 140,000'),
                    'annual_fee' => isset($p['fee_raw']) ? 'PKR '.number_format($p['fee_raw'] * 2) : ($p['annual_fee'] ?? 'PKR 280,000'),
                    'cutoff_merit' => $p['estimated_closing_merit'] ?? ($p['cutoff_merit'] ?? 'Competitive'),
                    'estimated_closing_merit' => $p['estimated_closing_merit'] ?? ($p['cutoff_merit'] ?? 'Competitive'),
                    'accreditation_council' => $p['council'] ?? ($p['accreditation_council'] ?? 'HEC'),
                    'accreditation_status' => $p['accreditation'] ?? ($p['accreditation_status'] ?? 'Accredited'),
                    'seat_capacity' => $p['seat_capacity'] ?? 60,
                    'eligibility_criteria' => $p['admission_criteria'] ?? ($p['eligibility_criteria'] ?? []),
                    'description' => $p['overview'] ?? ($p['description'] ?? null),
                    'curriculum_summary' => is_array($p['curriculum_highlights'] ?? null) ? implode("\n", $p['curriculum_highlights']) : ($p['curriculum_summary'] ?? null),
                    'career_prospects' => $p['career_outlook'] ?? ($p['career_prospects'] ?? null),
                    'featured' => $p['featured'] ?? false,
                    'status' => 'active',
                ]
            );
        }
    }
}
