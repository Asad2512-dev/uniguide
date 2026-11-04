<?php

namespace App\Services;

class StudentService
{
    public function __construct(
        protected DiscoveryService $discoveryService,
        protected AdmissionService $admissionService,
        protected CounselorService $counselorService
    ) {}

    /**
     * Retrieve the current student profile (session-backed with rich defaults).
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

        return session()->get('uniguide_student_profile', $defaultProfile);
    }

    /**
     * Save updated student profile into session.
     */
    public function updateProfile(array $data): array
    {
        $current = $this->getProfile();
        $updated = array_replace_recursive($current, $data);
        session()->put('uniguide_student_profile', $updated);

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
        $verifiedDocs = count(array_filter($profile['documents'], fn ($d) => $d['status'] === 'verified'));
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
     * Retrieve student's personal admission tracker applications.
     */
    public function getTrackerApplications(): array
    {
        $defaultApplications = [
            [
                'id' => 'app-1',
                'university_name' => 'National University of Sciences & Technology',
                'university_acronym' => 'NUST',
                'university_slug' => 'nust',
                'program_name' => 'BS Computer Science (SEECS)',
                'campus' => 'Islamabad Main Campus (H-12)',
                'stage' => 'applied', // interested, preparing, applied, test_scheduled, awaiting_result, offer_received, closed
                'stage_label' => 'Applied',
                'badge_color' => 'brand',
                'application_ref' => 'NUST-2026-UG-09412',
                'deadline' => '15 July 2026',
                'deadline_date' => '2026-07-15',
                'fee_challan_status' => 'Paid & Verified (PKR 5,000)',
                'test_date' => 'NET-4: 02 July 2026',
                'calculated_aggregate' => 77.95,
                'closing_cutoff_ref' => '79.24% (2025)',
                'notes' => 'NET-2 score of 152 logged. Target for NET-4 is 156+ to secure 1st merit list closing.',
                'last_updated' => '2 days ago',
            ],
            [
                'id' => 'app-2',
                'university_name' => 'FAST - National University of Computer & Emerging Sciences',
                'university_acronym' => 'FAST-NUCES',
                'university_slug' => 'fast-nuces',
                'program_name' => 'BS Software Engineering',
                'campus' => 'Islamabad Campus (H-9)',
                'stage' => 'test_scheduled',
                'stage_label' => 'Test Scheduled',
                'badge_color' => 'warning',
                'application_ref' => 'FAST-ISB-2026-4819',
                'deadline' => '05 July 2026',
                'deadline_date' => '2026-07-05',
                'fee_challan_status' => 'Challan Paid at Faysal Bank (PKR 3,500)',
                'test_date' => '08 July 2026 (Slot: 10:00 AM)',
                'calculated_aggregate' => 75.50,
                'closing_cutoff_ref' => '74.80% (2025)',
                'notes' => 'Admit slip downloaded. Revise advanced mathematics negative marking rules (-0.25).',
                'last_updated' => 'Yesterday',
            ],
            [
                'id' => 'app-3',
                'university_name' => 'COMSATS University Islamabad',
                'university_acronym' => 'CUI',
                'university_slug' => 'comsats',
                'program_name' => 'BS Artificial Intelligence',
                'campus' => 'Islamabad Main Campus (Park Road)',
                'stage' => 'preparing',
                'stage_label' => 'Preparing Application',
                'badge_color' => 'neutral',
                'application_ref' => 'Draft # CUI-2026-DRAFT',
                'deadline' => '20 July 2026',
                'deadline_date' => '2026-07-20',
                'fee_challan_status' => 'Pending Fee Challan Generation (PKR 2,500)',
                'test_date' => 'NTS-NAT Score (78/100) on record',
                'calculated_aggregate' => 83.60,
                'closing_cutoff_ref' => '82.10% (2025)',
                'notes' => 'Very high probability of 1st list admission based on 78 NAT score.',
                'last_updated' => '3 days ago',
            ],
            [
                'id' => 'app-4',
                'university_name' => 'Information Technology University',
                'university_acronym' => 'ITU Lahore',
                'university_slug' => 'itu-lahore',
                'program_name' => 'BS Computer Science',
                'campus' => 'Arfa Software Technology Park, Lahore',
                'stage' => 'offer_received',
                'stage_label' => 'Provisional Offer Received',
                'badge_color' => 'success',
                'application_ref' => 'ITU-ADM-2026-0042',
                'deadline' => 'Fee Submission by 10 August 2026',
                'deadline_date' => '2026-08-10',
                'fee_challan_status' => 'Admission Fee Challan Issued (PKR 165,000)',
                'test_date' => 'ITU Admissions Test cleared',
                'calculated_aggregate' => 84.10,
                'closing_cutoff_ref' => 'Rank #42 in Merit Cohort',
                'notes' => 'Provisional admission offer received under Early Decision round. Hold as prime safety.',
                'last_updated' => '4 hours ago',
            ],
            [
                'id' => 'app-5',
                'university_name' => 'Ghulam Ishaq Khan Institute of Engineering Sciences and Technology',
                'university_acronym' => 'GIKI',
                'university_slug' => 'giki',
                'program_name' => 'BS Computer Engineering',
                'campus' => 'Topi, Khyber Pakhtunkhwa',
                'stage' => 'interested',
                'stage_label' => 'Interested Shortlist',
                'badge_color' => 'indigo',
                'application_ref' => 'Not yet submitted',
                'deadline' => '10 June 2026',
                'deadline_date' => '2026-06-10',
                'fee_challan_status' => 'Application Fee PKR 4,000',
                'test_date' => 'GIKI Test: 28 June 2026',
                'calculated_aggregate' => 76.40,
                'closing_cutoff_ref' => 'High Test Weightage',
                'notes' => 'Full residential campus. Exploring GIKI Alumni need-based full scholarship scheme.',
                'last_updated' => '5 days ago',
            ],
        ];

        return session()->get('uniguide_student_tracker', $defaultApplications);
    }

    /**
     * Add a new application to the student tracker.
     */
    public function addTrackerApplication(array $data): array
    {
        $apps = $this->getTrackerApplications();
        $id = 'app-'.uniqid();

        $stageLabels = [
            'interested' => 'Interested Shortlist',
            'preparing' => 'Preparing Application',
            'applied' => 'Applied',
            'test_scheduled' => 'Test Scheduled',
            'awaiting_result' => 'Awaiting Merit List',
            'offer_received' => 'Offer Received',
            'closed' => 'Application Closed',
        ];

        $newApp = [
            'id' => $id,
            'university_name' => $data['university_name'] ?? 'University of the Punjab',
            'university_acronym' => $data['university_acronym'] ?? 'PU',
            'university_slug' => $data['university_slug'] ?? 'punjab-university',
            'program_name' => $data['program_name'] ?? 'BS Computer Science',
            'campus' => $data['campus'] ?? 'Main Campus, Lahore',
            'stage' => $data['stage'] ?? 'interested',
            'stage_label' => $stageLabels[$data['stage'] ?? 'interested'] ?? 'Interested',
            'badge_color' => match ($data['stage'] ?? 'interested') {
                'offer_received' => 'success',
                'test_scheduled' => 'warning',
                'applied' => 'brand',
                default => 'neutral',
            },
            'application_ref' => $data['application_ref'] ?? 'Pending Submission',
            'deadline' => $data['deadline'] ?? '30 July 2026',
            'deadline_date' => $data['deadline_date'] ?? '2026-07-30',
            'fee_challan_status' => $data['fee_challan_status'] ?? 'Unpaid',
            'test_date' => $data['test_date'] ?? 'TBD',
            'calculated_aggregate' => floatval($data['calculated_aggregate'] ?? 78.5),
            'closing_cutoff_ref' => $data['closing_cutoff_ref'] ?? 'Merit Archive',
            'notes' => $data['notes'] ?? 'Manual admission entry added by student.',
            'last_updated' => 'Just now',
        ];

        $apps[] = $newApp;
        session()->put('uniguide_student_tracker', $apps);

        return $newApp;
    }

    /**
     * Update an application in the tracker.
     */
    public function updateTrackerApplication(string $id, array $data): ?array
    {
        $apps = $this->getTrackerApplications();
        $updatedApp = null;

        foreach ($apps as $key => $app) {
            if ($app['id'] === $id) {
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
                    $data['badge_color'] = match ($data['stage']) {
                        'offer_received' => 'success',
                        'test_scheduled' => 'warning',
                        'applied' => 'brand',
                        default => 'neutral',
                    };
                }

                $data['last_updated'] = 'Just now';
                $apps[$key] = array_merge($app, $data);
                $updatedApp = $apps[$key];
                break;
            }
        }

        session()->put('uniguide_student_tracker', $apps);

        return $updatedApp;
    }

    /**
     * Remove an application from the tracker.
     */
    public function deleteTrackerApplication(string $id): bool
    {
        $apps = $this->getTrackerApplications();
        $filtered = array_values(array_filter($apps, fn ($a) => $a['id'] !== $id));
        session()->put('uniguide_student_tracker', $filtered);

        return count($filtered) < count($apps);
    }

    /**
     * Retrieve student's saved universities collection.
     */
    public function getSavedUniversities(string $sort = 'rank'): array
    {
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
                'slug' => 'comsats',
                'name' => 'COMSATS University Islamabad',
                'acronym' => 'CUI',
                'city' => 'Islamabad',
                'province_label' => 'Islamabad (ICT)',
                'sector' => 'public',
                'hec_ranking' => 'Top 5 National',
                'fee_range' => 'PKR 142,000 / sem',
                'fee_numeric' => 142000,
                'deadline' => '20 July 2026',
                'deadline_date' => '2026-07-20',
                'status' => 'open',
                'match_tier' => 'Solid Safety (83.6% NAT Aggregate)',
                'saved_at' => '2026-10-22',
            ],
            [
                'slug' => 'lums',
                'name' => 'Lahore University of Management Sciences',
                'acronym' => 'LUMS',
                'city' => 'Lahore',
                'province_label' => 'Punjab',
                'sector' => 'private',
                'hec_ranking' => '#1 Business & Premier Private',
                'fee_range' => 'PKR 540,000 / sem (NOP Aid Available)',
                'fee_numeric' => 540000,
                'deadline' => '30 January 2026',
                'deadline_date' => '2026-01-30',
                'status' => 'closed',
                'match_tier' => 'Reach (NOP Aid Qualified)',
                'saved_at' => '2026-09-15',
            ],
        ];

        $saved = session()->get('uniguide_student_saved_unis', $defaultSaved);

        if ($sort === 'fee_low') {
            usort($saved, fn ($a, $b) => ($a['fee_numeric'] ?? 0) <=> ($b['fee_numeric'] ?? 0));
        } elseif ($sort === 'deadline') {
            usort($saved, fn ($a, $b) => strcmp($a['deadline_date'] ?? '', $b['deadline_date'] ?? ''));
        }

        return $saved;
    }

    /**
     * Remove university from saved collection.
     */
    public function removeSavedUniversity(string $slug): void
    {
        $saved = $this->getSavedUniversities();
        $filtered = array_values(array_filter($saved, fn ($u) => $u['slug'] !== $slug));
        session()->put('uniguide_student_saved_unis', $filtered);
    }

    /**
     * Retrieve student's saved programs collection.
     */
    public function getSavedPrograms(): array
    {
        $defaultPrograms = [
            [
                'slug' => 'bs-computer-science-nust',
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
                'slug' => 'bs-software-engineering-fast',
                'name' => 'BS Software Engineering',
                'university_acronym' => 'FAST-NUCES',
                'university_name' => 'FAST - National University of Computer & Emerging Sciences',
                'university_slug' => 'fast-nuces',
                'campus' => 'Islamabad Campus (H-9)',
                'duration' => '4 Years (8 Semesters)',
                'fee_semester' => 'PKR 215,000',
                'calculated_aggregate' => 75.50,
                'closing_merit_ref' => '74.80%',
                'accreditation' => 'NCEAC Category W / PEC',
                'saved_at' => '20 Oct 2026',
            ],
            [
                'slug' => 'bs-artificial-intelligence-comsats',
                'name' => 'BS Artificial Intelligence',
                'university_acronym' => 'CUI',
                'university_name' => 'COMSATS University Islamabad',
                'university_slug' => 'comsats',
                'campus' => 'Park Road, Islamabad',
                'duration' => '4 Years (8 Semesters)',
                'fee_semester' => 'PKR 142,000',
                'calculated_aggregate' => 83.60,
                'closing_merit_ref' => '82.10%',
                'accreditation' => 'NCEAC Category W',
                'saved_at' => '22 Oct 2026',
            ],
        ];

        return session()->get('uniguide_student_saved_programs', $defaultPrograms);
    }

    /**
     * Remove program from saved collection.
     */
    public function removeSavedProgram(string $slug): void
    {
        $saved = $this->getSavedPrograms();
        $filtered = array_values(array_filter($saved, fn ($p) => $p['slug'] !== $slug));
        session()->put('uniguide_student_saved_programs', $filtered);
    }

    /**
     * Retrieve student's saved scholarships.
     */
    public function getSavedScholarships(): array
    {
        $defaultScholarships = [
            [
                'slug' => 'hec-need-based',
                'name' => 'HEC Need-Based Undergraduate Scholarship 2026',
                'authority' => 'Higher Education Commission of Pakistan',
                'coverage' => '100% Full Tuition Fee + PKR 6,000 / month living stipend',
                'eligibility_status' => 'Highly Likely (Household Income PKR 45,000 < PKR 45,000 Ceiling)',
                'badge' => 'Verified Need Match',
                'badge_color' => 'success',
                'deadline' => '31 August 2026',
                'saved_at' => '18 Oct 2026',
            ],
            [
                'slug' => 'ehsaas-undergraduate',
                'name' => 'Ehsaas Undergraduate Scholarship Project',
                'authority' => 'Benazir Income Support Programme (BISP) & HEC',
                'coverage' => 'Full Tuition Fee + PKR 5,000 stipend + Book Allowance',
                'eligibility_status' => 'Eligible Under National Poverty Scorecard',
                'badge' => 'Fully Funded',
                'badge_color' => 'success',
                'deadline' => '15 September 2026',
                'saved_at' => '25 Oct 2026',
            ],
            [
                'slug' => 'peef-scholarship',
                'name' => 'Punjab Educational Endowment Fund (PEEF)',
                'authority' => 'Government of the Punjab',
                'coverage' => '100% Tuition Fee + Boarding Subsidy',
                'eligibility_status' => 'Eligible for Punjab & Rawalpindi Domicile',
                'badge' => 'Provincial Endowment',
                'badge_color' => 'indigo',
                'deadline' => '30 September 2026',
                'saved_at' => '01 Nov 2026',
            ],
        ];

        return session()->get('uniguide_student_saved_scholarships', $defaultScholarships);
    }

    /**
     * Remove scholarship from saved collection.
     */
    public function removeSavedScholarship(string $slug): void
    {
        $saved = $this->getSavedScholarships();
        $filtered = array_values(array_filter($saved, fn ($s) => $s['slug'] !== $slug));
        session()->put('uniguide_student_saved_scholarships', $filtered);
    }

    /**
     * Retrieve student notifications.
     */
    public function getNotifications(string $filter = 'all'): array
    {
        $defaultNotifications = [
            [
                'id' => 'notif-1',
                'type' => 'deadline', // deadline, merit, scholarship, system
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
            [
                'id' => 'notif-3',
                'type' => 'scholarship',
                'title' => 'HEC Need-Based Scholarship Fall 2026 Cycle Open',
                'message' => 'Participating universities including NUST and COMSATS have begun accepting need-based financial aid forms.',
                'link' => route('scholarships.show', 'hec-need-based'),
                'time_ago' => '3 days ago',
                'read' => true,
                'urgent' => false,
            ],
            [
                'id' => 'notif-4',
                'type' => 'system',
                'title' => 'Aggregate Calibrated: 77.95% for NUST SEECS',
                'message' => 'Your target aggregate was recalculated using your newly verified NET-2 score of 152.',
                'link' => route('counselor.chat', 'conv-1'),
                'time_ago' => '5 days ago',
                'read' => true,
                'urgent' => false,
            ],
        ];

        $notifications = session()->get('uniguide_student_notifications', $defaultNotifications);

        if ($filter !== 'all') {
            $notifications = array_values(array_filter($notifications, fn ($n) => $n['type'] === $filter));
        }

        return $notifications;
    }

    /**
     * Mark notification as read.
     */
    public function markNotificationRead(string $id): void
    {
        $notifications = $this->getNotifications();
        foreach ($notifications as &$n) {
            if ($n['id'] === $id) {
                $n['read'] = true;
                break;
            }
        }
        session()->put('uniguide_student_notifications', $notifications);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllNotificationsRead(): void
    {
        $notifications = $this->getNotifications();
        foreach ($notifications as &$n) {
            $n['read'] = true;
        }
        session()->put('uniguide_student_notifications', $notifications);
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
