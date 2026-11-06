<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Program;
use App\Models\SavedItem;
use App\Models\StudentApplication;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class StudentService
{
    public function __construct(
        protected DiscoveryService $discoveryService,
        protected AdmissionService $admissionService,
        protected CounselorService $counselorService
    ) {}

    protected function getCurrentStudent(): ?User
    {
        $user = auth()->user();
        if ($user && $user->role === 'student') {
            return $user;
        }

        return null;
    }

    /**
     * Retrieve the current student profile (database-backed with rich defaults).
     */
    public function getProfile(): array
    {
        $defaultProfile = [
            'full_name' => 'Ahmad Raza Khan',
            'applicant_id' => 'UG-2026-8841',
            'email' => 'ahmad.raza.khan@student.pk',
            'phone' => '+92 300 5558841',
            'city' => 'Rawalpindi',
            'domicile_province' => 'ict',
            'domicile_label' => 'Islamabad Capital Territory / Rawalpindi',
            'cnic' => '61101-9876543-1',
            'gender' => 'Male',
            'dob' => '2007-04-12',
            'stream' => 'pre_engineering',
            'stream_label' => 'FSc Pre-Engineering',
            'monthly_household_income' => 45000,
            'avatar_initials' => 'AR',
            'onboarding_completed' => true,

            // Academic Records
            'matric' => [
                'degree' => 'Matriculation (SSC)',
                'board' => 'Federal Board of Intermediate & Secondary Education (FBISE)',
                'year' => 2024,
                'total_marks' => 1100,
                'obtained_marks' => 968,
                'percentage' => 88.0,
                'grade' => 'A1 / Exceptional',
                'roll_number' => '284192',
                'verified' => true,
            ],
            'fsc' => [
                'degree' => 'FSc (Pre-Engineering) Part-1',
                'board' => 'Federal Board of Intermediate & Secondary Education (FBISE)',
                'year' => 2025,
                'total_marks' => 520,
                'obtained_marks' => 421,
                'percentage' => 81.0,
                'grade' => 'A / Excellent',
                'roll_number' => '719204',
                'verified' => true,
                'expected_part2_percentage' => 82.5,
            ],

            // Entry Tests Records
            'entry_tests' => [
                [
                    'name' => 'NUST Entry Test (NET-2)',
                    'test_type' => 'net',
                    'test_date' => '18 March 2026',
                    'score' => 152,
                    'max_score' => 200,
                    'percentage' => 76.0,
                    'roll_number' => 'NET-2026-8841',
                    'status' => 'Verified Score',
                ],
                [
                    'name' => 'FAST-NU Computing Admission Test',
                    'test_type' => 'fast',
                    'test_date' => 'Upcoming (08 July 2026)',
                    'score' => 74,
                    'max_score' => 100,
                    'percentage' => 74.0,
                    'roll_number' => 'NU-ISB-99201',
                    'status' => 'Admit Card Issued',
                ],
                [
                    'name' => 'NTS National Aptitude Test (NAT-IE)',
                    'test_type' => 'nat',
                    'test_date' => '22 February 2026',
                    'score' => 78,
                    'max_score' => 100,
                    'percentage' => 78.0,
                    'roll_number' => 'NTS-2026-1149',
                    'status' => 'Official Result Card Received',
                ],
            ],

            // Documents Verification
            'documents' => [
                [
                    'id' => 'doc-1',
                    'title' => 'Matriculation (SSC) Official Sanad & Result Card',
                    'status' => 'verified',
                    'status_label' => 'IBCC & Board Verified',
                    'file_name' => 'ahmad_matric_fbise_sanad.pdf',
                    'uploaded_at' => '15 Jan 2026',
                ],
                [
                    'id' => 'doc-2',
                    'title' => 'HSSC / FSc Part-1 Official Mark Sheet',
                    'status' => 'verified',
                    'status_label' => 'Verified Attestation',
                    'file_name' => 'ahmad_fsc_part1_fbise.pdf',
                    'uploaded_at' => '15 Jan 2026',
                ],
                [
                    'id' => 'doc-3',
                    'title' => 'National Identity Card (Smart CNIC / B-Form)',
                    'status' => 'verified',
                    'status_label' => 'NADRA Verified',
                    'file_name' => 'cnic_6110198765431.pdf',
                    'uploaded_at' => '18 Jan 2026',
                ],
                [
                    'id' => 'doc-4',
                    'title' => 'Father\'s Attested Income Certificate / Pension Slip',
                    'status' => 'pending',
                    'status_label' => 'Pending Verification for HEC Aid',
                    'file_name' => 'salary_affidavit_45k.pdf',
                    'uploaded_at' => '02 Feb 2026',
                ],
                [
                    'id' => 'doc-5',
                    'title' => 'Domicile Certificate (ICT / Punjab)',
                    'status' => 'verified',
                    'status_label' => 'Verified',
                    'file_name' => 'domicile_rawalpindi.pdf',
                    'uploaded_at' => '20 Jan 2026',
                ],
            ],

            // Preferences
            'preferences' => [
                'target_disciplines' => ['Computing & Artificial Intelligence', 'Software Engineering', 'Data Science'],
                'target_cities' => ['Islamabad', 'Rawalpindi', 'Lahore'],
                'budget_max' => 200000,
                'university_types' => ['public', 'chartered'],
                'needs_hostel' => true,
                'scholarship_interest' => 'need_and_merit',
            ],
        ];

        $student = $this->getCurrentStudent();
        if ($student) {
            $dbProfile = StudentProfile::where('user_id', $student->id)->first();
            if ($dbProfile) {
                $merged = $defaultProfile;
                $merged['full_name'] = $dbProfile->full_name ?? $student->name ?? $defaultProfile['full_name'];
                $merged['applicant_id'] = $dbProfile->applicant_id ?? $defaultProfile['applicant_id'];
                $merged['email'] = $student->email ?? $defaultProfile['email'];
                $merged['phone'] = $dbProfile->phone ?? $student->phone ?? $defaultProfile['phone'];
                $merged['city'] = $dbProfile->city ?? $defaultProfile['city'];
                $merged['domicile_province'] = $dbProfile->domicile_province ?? $defaultProfile['domicile_province'];
                $merged['domicile_label'] = $dbProfile->domicile_label ?? $defaultProfile['domicile_label'];
                $merged['cnic'] = $dbProfile->cnic ?? $defaultProfile['cnic'];
                $merged['gender'] = $dbProfile->gender ?? $defaultProfile['gender'];
                $merged['stream'] = $dbProfile->stream ?? $defaultProfile['stream'];
                $merged['stream_label'] = $dbProfile->stream_label ?? $defaultProfile['stream_label'];
                $merged['monthly_household_income'] = $dbProfile->monthly_household_income ?? $defaultProfile['monthly_household_income'];
                $merged['onboarding_completed'] = (bool) $dbProfile->onboarding_completed;

                if (! empty($dbProfile->matric_data)) {
                    $merged['matric'] = array_merge($defaultProfile['matric'], $dbProfile->matric_data);
                }
                if (! empty($dbProfile->fsc_data)) {
                    $merged['fsc'] = array_merge($defaultProfile['fsc'], $dbProfile->fsc_data);
                }
                if (! empty($dbProfile->entry_tests_data)) {
                    $merged['entry_tests'] = $dbProfile->entry_tests_data;
                }
                if (! empty($dbProfile->preferences)) {
                    $merged['preferences'] = array_merge($defaultProfile['preferences'], $dbProfile->preferences);
                }

                // Session overrides if present
                $sessionOverrides = session()->get('uniguide_student_profile', []);

                return array_replace_recursive($merged, $sessionOverrides);
            }
        }

        return session()->get('uniguide_student_profile', $defaultProfile);
    }

    /**
     * Save updated student profile into database and session.
     */
    public function updateProfile(array $data): array
    {
        $current = $this->getProfile();
        $updated = array_replace_recursive($current, $data);
        session()->put('uniguide_student_profile', $updated);

        $student = $this->getCurrentStudent();
        if ($student) {
            StudentProfile::updateOrCreate(
                ['user_id' => $student->id],
                [
                    'full_name' => $updated['full_name'] ?? $student->name,
                    'phone' => $updated['phone'] ?? $student->phone,
                    'city' => $updated['city'] ?? 'Rawalpindi',
                    'domicile_province' => $updated['domicile_province'] ?? 'ict',
                    'domicile_label' => $updated['domicile_label'] ?? null,
                    'cnic' => $updated['cnic'] ?? null,
                    'gender' => $updated['gender'] ?? 'Male',
                    'stream' => $updated['stream'] ?? 'pre_engineering',
                    'stream_label' => $updated['stream_label'] ?? null,
                    'monthly_household_income' => $updated['monthly_household_income'] ?? null,
                    'matric_data' => $updated['matric'] ?? [],
                    'fsc_data' => $updated['fsc'] ?? [],
                    'entry_tests_data' => $updated['entry_tests'] ?? [],
                    'preferences' => $updated['preferences'] ?? [],
                    'onboarding_completed' => true,
                ]
            );
        }

        return $updated;
    }

    /**
     * Compute academic profile completeness percentage and missing checklist.
     */
    public function getCompleteness(): array
    {
        $profile = $this->getProfile();
        $score = 0;
        $missing = [];

        // Matric check
        if (! empty($profile['matric']['obtained_marks'])) {
            $score += 20;
        } else {
            $missing[] = 'Enter your Matriculation / O-Level total and obtained marks';
        }

        // FSc check
        if (! empty($profile['fsc']['obtained_marks'])) {
            $score += 25;
        } else {
            $missing[] = 'Enter your FSc Part-1 / A-Level marks';
        }

        // Entry tests check
        if (! empty($profile['entry_tests'])) {
            $score += 25;
        } else {
            $missing[] = 'Record at least one entry test score (NET, ECAT, or NAT)';
        }

        // Documents check
        $docs = $profile['documents'] ?? [];
        $verifiedDocs = count(array_filter($docs, fn ($d) => ($d['status'] ?? '') === 'verified'));
        if ($verifiedDocs >= 3) {
            $score += 20;
        } else {
            $missing[] = 'Upload attested copies of required admission documents';
        }

        // Income / Aid check
        if (! empty($profile['monthly_household_income'])) {
            $score += 10;
        } else {
            $missing[] = 'Declare monthly household income for scholarship qualification';
        }

        return [
            'percentage' => min(100, $score),
            'missing_items' => $missing,
            'is_ready_for_admissions' => $score >= 75,
        ];
    }

    /**
     * Retrieve student's personal admission tracker applications from database.
     */
    public function getTrackerApplications(): array
    {
        $student = $this->getCurrentStudent();
        if ($student) {
            $dbApps = StudentApplication::where('user_id', $student->id)->get();
            if ($dbApps->isNotEmpty()) {
                $mapped = [];
                foreach ($dbApps as $app) {
                    $mapped[] = [
                        'id' => (string) $app->id,
                        'university_name' => $app->university_name,
                        'university_acronym' => $app->university_acronym ?? 'HEC',
                        'university_slug' => $app->university_slug ?? 'uni',
                        'program_name' => $app->program_name,
                        'campus' => $app->campus ?? 'Main Campus',
                        'stage' => $app->stage,
                        'stage_label' => Program::whereKey($app->program_id)->exists() ? ($app->stage_label ?? ucfirst(str_replace('_', ' ', $app->stage))) : 'Archived — outside current coverage',
                        'badge_color' => match ($app->stage) {
                            'offer_received' => 'success',
                            'test_scheduled' => 'warning',
                            'applied' => 'brand',
                            default => 'neutral',
                        },
                        'application_ref' => $app->application_ref,
                        'deadline' => $app->deadline ?? 'Open',
                        'deadline_date' => $app->deadline_date?->format('Y-m-d') ?? null,
                        'fee_challan_status' => $app->fee_challan_status ?? 'Unpaid',
                        'test_date' => $app->test_date ?? 'TBD',
                        'calculated_aggregate' => floatval($app->calculated_aggregate ?? 0.0),
                        'closing_cutoff_ref' => $app->closing_cutoff_ref ?? 'Merit Archive',
                        'notes' => $app->notes ?? '',
                        'last_updated' => $app->updated_at?->diffForHumans() ?? 'Recently',
                    ];
                }

                return $mapped;
            }
        }

        return [];
    }

    /**
     * Add an application to the student admission tracker.
     */
    public function addTrackerApplication(array $data): array
    {
        abort_unless($this->getCurrentStudent(), 401);
        $program = Program::with(['university', 'campus'])->find($data['program_id'] ?? 0);
        if (! $program) {
            throw ValidationException::withMessages(['program_id' => 'Select a recorded offering at a campus in Punjab or Islamabad.']);
        }
        $data = array_replace($data, ['university_id' => $program->university_id, 'program_id' => $program->id, 'university_name' => $program->university->name, 'university_acronym' => $program->university->acronym, 'university_slug' => $program->university->slug, 'program_name' => $program->name, 'campus' => $program->campus->name]);
        $stageLabels = [
            'interested' => 'Interested Shortlist',
            'preparing' => 'Preparing Application',
            'applied' => 'Applied',
            'test_scheduled' => 'Test Scheduled',
            'awaiting_result' => 'Awaiting Merit List',
            'offer_received' => 'Offer Received',
            'closed' => 'Application Closed',
        ];

        $stage = $data['stage'] ?? 'interested';
        $stageLabel = $stageLabels[$stage] ?? 'Interested';
        $student = $this->getCurrentStudent();

        $app = StudentApplication::create([
            'university_id' => $program->university_id, 'program_id' => $program->id,
            'user_id' => $student?->id,
            'application_ref' => $data['application_ref'] ?? ('APP-'.strtoupper(substr(md5(uniqid()), 0, 8))),
            'university_name' => $data['university_name'] ?? 'University of the Punjab',
            'university_acronym' => $data['university_acronym'] ?? 'PU',
            'university_slug' => $data['university_slug'] ?? 'punjab-university',
            'program_name' => $data['program_name'] ?? 'BS Computer Science',
            'campus' => $data['campus'] ?? 'Main Campus, Lahore',
            'stage' => $stage,
            'stage_label' => $stageLabel,
            'deadline' => $data['deadline'] ?? '30 July 2026',
            'deadline_date' => ! empty($data['deadline_date']) ? $data['deadline_date'] : null,
            'fee_challan_status' => $data['fee_challan_status'] ?? 'Unpaid',
            'test_date' => $data['test_date'] ?? 'TBD',
            'calculated_aggregate' => floatval($data['calculated_aggregate'] ?? 78.5),
            'closing_cutoff_ref' => $data['closing_cutoff_ref'] ?? 'Merit Archive',
            'notes' => $data['notes'] ?? 'Manual admission entry added by student.',
        ]);

        return [
            'id' => (string) $app->id,
            'university_name' => $app->university_name,
            'university_acronym' => $app->university_acronym,
            'university_slug' => $app->university_slug,
            'program_name' => $app->program_name,
            'campus' => $app->campus,
            'stage' => $app->stage,
            'stage_label' => $app->stage_label,
            'badge_color' => match ($app->stage) {
                'offer_received' => 'success',
                'test_scheduled' => 'warning',
                'applied' => 'brand',
                default => 'neutral',
            },
            'application_ref' => $app->application_ref,
            'deadline' => $app->deadline,
            'deadline_date' => $app->deadline_date?->format('Y-m-d'),
            'fee_challan_status' => $app->fee_challan_status,
            'test_date' => $app->test_date,
            'calculated_aggregate' => floatval($app->calculated_aggregate),
            'closing_cutoff_ref' => $app->closing_cutoff_ref,
            'notes' => $app->notes,
            'last_updated' => 'Just now',
        ];
    }

    /**
     * Update an application in the tracker.
     */
    public function updateTrackerApplication(string $id, array $data): ?array
    {
        $student = $this->getCurrentStudent();
        $numericId = (int) str_replace('app-', '', $id);

        $app = StudentApplication::where('id', $numericId)
            ->orWhere('application_ref', $id)
            ->first();

        if ($app) {
            $stageLabels = [
                'interested' => 'Interested Shortlist',
                'preparing' => 'Preparing Application',
                'applied' => 'Applied',
                'test_scheduled' => 'Test Scheduled',
                'awaiting_result' => 'Awaiting Merit List',
                'offer_received' => 'Offer Received',
                'closed' => 'Application Closed',
            ];

            if (isset($data['stage'])) {
                $data['stage_label'] = $stageLabels[$data['stage']] ?? 'Updated';
            }

            $app->update($data);

            return [
                'id' => (string) $app->id,
                'university_name' => $app->university_name,
                'university_acronym' => $app->university_acronym,
                'university_slug' => $app->university_slug,
                'program_name' => $app->program_name,
                'campus' => $app->campus,
                'stage' => $app->stage,
                'stage_label' => $app->stage_label,
                'badge_color' => match ($app->stage) {
                    'offer_received' => 'success',
                    'test_scheduled' => 'warning',
                    'applied' => 'brand',
                    default => 'neutral',
                },
                'application_ref' => $app->application_ref,
                'deadline' => $app->deadline,
                'deadline_date' => $app->deadline_date?->format('Y-m-d'),
                'fee_challan_status' => $app->fee_challan_status,
                'test_date' => $app->test_date,
                'calculated_aggregate' => floatval($app->calculated_aggregate),
                'closing_cutoff_ref' => $app->closing_cutoff_ref,
                'notes' => $app->notes,
                'last_updated' => 'Just now',
            ];
        }

        return null;
    }

    /**
     * Remove an application from the tracker.
     */
    public function deleteTrackerApplication(string $id): bool
    {
        $numericId = (int) str_replace('app-', '', $id);
        $deleted = StudentApplication::where('id', $numericId)
            ->orWhere('application_ref', $id)
            ->delete();

        return $deleted > 0;
    }

    /**
     * Retrieve student's saved universities collection from database.
     */
    public function getSavedUniversities(string $sort = 'rank'): array
    {
        $student = $this->getCurrentStudent();
        $items = [];

        if ($student) {
            $saved = SavedItem::where('user_id', $student->id)
                ->where('item_type', 'university')
                ->get();

            foreach ($saved as $item) {
                if (! empty($item->metadata)) {
                    $items[] = $item->metadata;
                }
            }
        }

        if (empty($items)) {
            $defaultSaved = [
                [
                    'slug' => 'nust',
                    'name' => 'National University of Sciences & Technology',
                    'acronym' => 'NUST',
                    'city' => 'Islamabad',
                    'province_label' => 'Islamabad (ICT)',
                    'sector' => 'public',
                    'hec_ranking' => '#1 Overall Engineering',
                    'fee_range' => 'PKR 195,000 / sem',
                    'fee_numeric' => 195000,
                    'deadline' => '15 July 2026',
                    'deadline_date' => '2026-07-15',
                    'status' => 'open',
                    'match_tier' => 'Reach (NET 155+ Target)',
                    'saved_at' => '2026-10-18',
                ],
                [
                    'slug' => 'fast-nuces',
                    'name' => 'FAST - National University of Computer & Emerging Sciences',
                    'acronym' => 'FAST-NUCES',
                    'city' => 'Islamabad',
                    'province_label' => 'Islamabad (ICT)',
                    'sector' => 'chartered',
                    'hec_ranking' => '#1 Computing & AI',
                    'fee_range' => 'PKR 215,000 / sem',
                    'fee_numeric' => 215000,
                    'deadline' => '05 July 2026',
                    'deadline_date' => '2026-07-05',
                    'status' => 'open',
                    'match_tier' => 'Target Match (75.5% vs 74.8% Cutoff)',
                    'saved_at' => '2026-10-20',
                ],
                [
                    'slug' => 'lums',
                    'name' => 'Lahore University of Management Sciences',
                    'acronym' => 'LUMS',
                    'city' => 'Lahore',
                    'province_label' => 'Punjab',
                    'sector' => 'private',
                    'hec_ranking' => '#1 Business & Economics',
                    'fee_range' => 'PKR 485,000 / sem',
                    'fee_numeric' => 485000,
                    'deadline' => '30 January 2026',
                    'deadline_date' => '2026-01-30',
                    'status' => 'closed',
                    'match_tier' => 'Reach (LCAT / SAT Competitive)',
                    'saved_at' => '2026-10-22',
                ],
            ];

            $items = session()->get('uniguide_student_saved_unis', $defaultSaved);
        }

        return app(RegionalStudentCatalog::class)->activeSaved($items, 'university');
    }

    /**
     * Remove university from saved collection.
     */
    public function removeSavedUniversity(string $slug): void
    {
        $student = $this->getCurrentStudent();
        if ($student) {
            SavedItem::where('user_id', $student->id)
                ->where('item_type', 'university')
                ->where('item_slug', $slug)
                ->delete();
        }

        $saved = session()->get('uniguide_student_saved_unis', []);
        $filtered = array_values(array_filter($saved, fn ($u) => ($u['slug'] ?? '') !== $slug));
        session()->put('uniguide_student_saved_unis', $filtered);
    }

    /**
     * Retrieve student's saved programs collection from database.
     */
    public function getSavedPrograms(): array
    {
        $student = $this->getCurrentStudent();
        $items = [];

        if ($student) {
            $saved = SavedItem::where('user_id', $student->id)
                ->where('item_type', 'program')
                ->get();

            foreach ($saved as $item) {
                if (! empty($item->metadata)) {
                    $items[] = $item->metadata;
                }
            }
        }

        if (empty($items)) {
            $defaultPrograms = [
                [
                    'slug' => 'nust-bs-computer-science',
                    'name' => 'BS Computer Science',
                    'university_acronym' => 'NUST',
                    'university_name' => 'National University of Sciences & Technology',
                    'university_slug' => 'nust',
                    'campus' => 'SEECS, Islamabad (H-12)',
                    'duration' => '4 Years (8 Semesters)',
                    'fee_semester' => 'PKR 195,000',
                    'calculated_aggregate' => 77.95,
                    'closing_merit_ref' => '79.24%',
                    'accreditation' => 'NCEAC Category W',
                    'saved_at' => '18 Oct 2026',
                ],
                [
                    'slug' => 'fast-bs-software-engineering',
                    'name' => 'BS Software Engineering',
                    'university_acronym' => 'FAST-NUCES',
                    'university_name' => 'FAST - National University of Computer & Emerging Sciences',
                    'university_slug' => 'fast-nuces',
                    'campus' => 'Islamabad Campus (H-9)',
                    'duration' => '4 Years (8 Semesters)',
                    'fee_semester' => 'PKR 215,000',
                    'calculated_aggregate' => 75.50,
                    'closing_merit_ref' => '74.80%',
                    'accreditation' => 'NCEAC Category W',
                    'saved_at' => '20 Oct 2026',
                ],
            ];

            $items = session()->get('uniguide_student_saved_programs', $defaultPrograms);
        }

        return app(RegionalStudentCatalog::class)->activeSaved($items, 'program');
    }

    /**
     * Remove program from saved collection.
     */
    public function removeSavedProgram(string $slug): void
    {
        $student = $this->getCurrentStudent();
        if ($student) {
            SavedItem::where('user_id', $student->id)
                ->where('item_type', 'program')
                ->where('item_slug', $slug)
                ->delete();
        }

        $saved = session()->get('uniguide_student_saved_programs', []);
        $filtered = array_values(array_filter($saved, fn ($p) => ($p['slug'] ?? '') !== $slug));
        session()->put('uniguide_student_saved_programs', $filtered);
    }

    /**
     * Retrieve student's saved scholarships from database.
     */
    public function getSavedScholarships(): array
    {
        $student = $this->getCurrentStudent();
        $items = [];

        if ($student) {
            $saved = SavedItem::where('user_id', $student->id)
                ->where('item_type', 'scholarship')
                ->get();

            foreach ($saved as $item) {
                if (! empty($item->metadata)) {
                    $items[] = $item->metadata;
                }
            }
        }

        if (empty($items)) {
            $defaultScholarships = [
                [
                    'slug' => 'hec-need-based',
                    'name' => 'HEC Need-Based Undergraduate Scholarship 2026',
                    'provider' => 'Higher Education Commission (HEC)',
                    'type' => 'Need-Based Full Tuition + Monthly Stipend',
                    'coverage' => '100% Tuition + PKR 6,000 / month living stipend',
                    'coverage_numeric' => 195000,
                    'status' => 'Eligible & Recommended',
                    'eligibility_status' => 'Eligible & Recommended',
                    'badge' => 'National Merit',
                    'badge_color' => 'success',
                    'deadline' => '25 July 2026',
                    'saved_at' => '22 Oct 2026',
                ],
                [
                    'slug' => 'peef-special-quota',
                    'name' => 'Punjab Educational Endowment Fund (PEEF) Master Grant',
                    'provider' => 'Government of the Punjab',
                    'type' => 'Provincial Need & Merit Quota',
                    'coverage' => '100% Tuition Subsidy + Boarding Facility',
                    'coverage_numeric' => 150000,
                    'status' => 'Eligible for Punjab & Rawalpindi Domicile',
                    'eligibility_status' => 'Eligible for Punjab & Rawalpindi Domicile',
                    'badge' => 'Provincial Endowment',
                    'badge_color' => 'indigo',
                    'deadline' => '30 September 2026',
                    'saved_at' => '01 Nov 2026',
                ],
            ];

            $items = session()->get('uniguide_student_saved_scholarships', $defaultScholarships);
        }

        return app(RegionalStudentCatalog::class)->activeSaved($items, 'scholarship');
    }

    /**
     * Remove scholarship from saved collection.
     */
    public function removeSavedScholarship(string $slug): void
    {
        $student = $this->getCurrentStudent();
        if ($student) {
            SavedItem::where('user_id', $student->id)
                ->where('item_type', 'scholarship')
                ->where('item_slug', $slug)
                ->delete();
        }

        $saved = session()->get('uniguide_student_saved_scholarships', []);
        $filtered = array_values(array_filter($saved, fn ($s) => ($s['slug'] ?? '') !== $slug));
        session()->put('uniguide_student_saved_scholarships', $filtered);
    }

    /**
     * Retrieve student notifications from database.
     */
    public function getNotifications(string $filter = 'all'): array
    {
        $student = $this->getCurrentStudent();
        $items = [];

        if ($student) {
            $query = Notification::where('user_id', $student->id)->latest('dispatched_at');
            if ($filter !== 'all') {
                $query->where('category', $filter);
            }

            $dbNotifs = $query->get();
            if ($dbNotifs->isNotEmpty()) {
                foreach ($dbNotifs as $n) {
                    $items[] = [
                        'id' => (string) $n->id,
                        'type' => $n->category ?? 'general',
                        'title' => $n->title,
                        'message' => $n->message ?? '',
                        'link' => route('student.dashboard'),
                        'time_ago' => $n->dispatched_at?->diffForHumans() ?? 'Recently',
                        'read' => ! empty($n->read_at),
                        'urgent' => ($n->category === 'deadline'),
                    ];
                }

                return $items;
            }
        }

        // Fallback session/default
        $defaultNotifications = [
            [
                'id' => 'notif-1',
                'type' => 'deadline',
                'title' => 'NUST NET-4 Registration Closes in 5 Days',
                'message' => 'Online registration for NET Series 4 closes on 15 July 2026. Submit your application challan to confirm test slot.',
                'link' => route('admissions.show', 'nust-fall-2026'),
                'time_ago' => '2 hours ago',
                'read' => false,
                'urgent' => true,
            ],
            [
                'id' => 'notif-2',
                'type' => 'merit',
                'title' => 'FAST-NU Entry Test Admit Slip Generated',
                'message' => 'Your test admit slip for BS Software Engineering (Roll #NU-ISB-99201) is ready. Exam center: H-9 Campus Islamabad.',
                'link' => route('student.tracker'),
                'time_ago' => '1 day ago',
                'read' => false,
                'urgent' => false,
            ],
        ];

        return session()->get('uniguide_student_notifications', $defaultNotifications);
    }

    /**
     * Mark notification as read.
     */
    public function markNotificationRead(string $id): void
    {
        $student = $this->getCurrentStudent();
        if ($student) {
            $numericId = (int) str_replace('notif-', '', $id);
            Notification::where('user_id', $student->id)
                ->where('id', $numericId)
                ->update(['read_at' => now()]);
        }
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllNotificationsRead(): void
    {
        $student = $this->getCurrentStudent();
        if ($student) {
            Notification::where('user_id', $student->id)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }
    }

    /**
     * Retrieve personalized university recommendations tailored to this authenticated student.
     */
    public function getPersonalizedRecommendations(): array
    {
        $profile = $this->getProfile();

        $counselorProfile = [
            'matric_percentage' => $profile['matric']['percentage'] ?? 88.0,
            'fsc_percentage' => $profile['fsc']['percentage'] ?? 81.0,
            'test_type' => 'net',
            'test_score' => 152,
            'discipline' => 'computing',
            'location' => 'islamabad',
            'budget_max' => $profile['preferences']['budget_max'] ?? 200000,
            'monthly_income' => $profile['monthly_household_income'] ?? 45000,
        ];

        return $this->counselorService->evaluateStudent($counselorProfile);
    }
}
