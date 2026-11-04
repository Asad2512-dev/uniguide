<?php

namespace App\Services;

class UniversityAdminService
{
    protected string $institutionSlug = 'nust';

    protected string $institutionCode = 'INST-HEC-PK-019';

    protected string $institutionName = 'National University of Sciences & Technology (NUST)';

    /**
     * Get active institution metadata.
     */
    public function getInstitution(): array
    {
        return [
            'slug' => $this->institutionSlug,
            'code' => $this->institutionCode,
            'name' => $this->institutionName,
            'acronym' => 'NUST',
            'sector' => 'public',
            'charter_act' => 'Federal Charter Act No. XXVIII of 1991',
            'established' => 1991,
            'hec_ranking' => '#1 Overall Engineering & Technology',
            'chancellor' => 'President of the Islamic Republic of Pakistan',
            'rector' => 'Lt Gen Javed Mahmood Bukhari HI(M) (Retd)',
            'director_admissions' => 'Dr. Tariq Mahmood',
            'website' => 'https://nust.edu.pk',
            'admissions_portal' => 'https://ugadmissions.nust.edu.pk',
            'admissions_email' => 'admissions@nust.edu.pk',
            'helpline' => '+92 51 111-11-6878',
            'address' => 'Sector H-12, Islamabad, 44000, Pakistan',
            'accreditations' => ['HEC W4', 'PEC Level-II Washington Accord', 'NCEAC W Category', 'NBEAC W Category'],
            'verified_date' => '04 November 2026',
        ];
    }

    /**
     * 1. Dashboard Overview Metrics & Statuses.
     */
    public function getDashboardData(): array
    {
        $profileCompleteness = 94; // %
        $missingItems = [
            '2026-2027 Internal Hostel Tariff Gazette upload pending',
        ];

        $kpis = [
            [
                'title' => 'Profile Completeness',
                'value' => '94%',
                'change' => '+4% this month',
                'changeType' => 'increase',
                'icon' => 'check-circle',
                'iconColor' => 'success',
            ],
            [
                'title' => 'Active Programs',
                'value' => '42',
                'change' => 'Across 7 Schools',
                'changeType' => 'neutral',
                'icon' => 'book-open',
                'iconColor' => 'brand',
            ],
            [
                'title' => 'Fall 2026 Applicants',
                'value' => '12,840',
                'change' => '+18.5% YoY',
                'changeType' => 'increase',
                'icon' => 'users',
                'iconColor' => 'brand',
            ],
            [
                'title' => 'AI Verification Queue',
                'value' => '4 Pending',
                'change' => 'Requires Audit',
                'changeType' => 'warning',
                'icon' => 'sparkles',
                'iconColor' => 'warning',
            ],
        ];

        $activeSessions = [
            [
                'id' => 'ses-1',
                'name' => 'Fall 2026 Undergraduate Cycle',
                'status' => 'open',
                'status_label' => 'Applications Open',
                'deadline' => '15 July 2026',
                'days_left' => 42,
                'seats' => 3450,
                'applications_count' => 12840,
                'test_series' => 'NET-4 Series (02-14 July 2026)',
            ],
            [
                'id' => 'ses-2',
                'name' => 'Fall 2026 Postgraduate (MS/PhD)',
                'status' => 'open',
                'status_label' => 'Applications Open',
                'deadline' => '25 July 2026',
                'days_left' => 52,
                'seats' => 1200,
                'applications_count' => 3890,
                'test_series' => 'GAT Subject & NUST G-NET',
            ],
        ];

        $recentActivity = [
            [
                'id' => 'act-1',
                'action' => 'Tuition fee schedule updated for BS Computer Science',
                'user' => 'Dr. Tariq Mahmood (Admissions)',
                'timestamp' => '2 hours ago',
                'type' => 'fee_update',
                'icon' => 'receipt',
            ],
            [
                'id' => 'act-2',
                'action' => 'AI Extracted 4 updates from 2026 Prospectus PDF',
                'user' => 'Automated Gazette Crawler',
                'timestamp' => '5 hours ago',
                'type' => 'ai_extracted',
                'icon' => 'sparkles',
            ],
            [
                'id' => 'act-3',
                'action' => 'Washington Accord PEC Level-II status re-affirmed for BE Electrical',
                'user' => 'Prof. Naveed Arshad (Registrar)',
                'timestamp' => '1 day ago',
                'type' => 'accreditation',
                'icon' => 'shield-check',
            ],
            [
                'id' => 'act-4',
                'action' => '14 new student admission inquiries assigned to triage desk',
                'user' => 'System Router',
                'timestamp' => 'Yesterday',
                'type' => 'inquiry',
                'icon' => 'message-square',
            ],
        ];

        $suggestedActions = [
            [
                'title' => 'Audit 4 Pending AI Scraped Data Fields',
                'desc' => 'Automated crawler detected tuition adjustments in the new prospectus release.',
                'route' => 'uni_admin.ai_verification',
                'cta' => 'Review Queue',
                'priority' => 'high',
                'icon' => 'sparkles',
            ],
            [
                'title' => 'Declare Fall 2026 Round-1 Merit Cutoffs',
                'desc' => 'NET-3 scores compiled. Cutoff lists ready for publication.',
                'route' => 'uni_admin.admissions',
                'cta' => 'Manage Admissions',
                'priority' => 'medium',
                'icon' => 'calendar-range',
            ],
            [
                'title' => 'Review HEC Need-Based Financial Aid Disbursal',
                'desc' => '180 candidate applications awaiting institutional committee signoff.',
                'route' => 'uni_admin.scholarships',
                'cta' => 'Audit Scholarships',
                'priority' => 'medium',
                'icon' => 'award',
            ],
        ];

        return [
            'institution' => $this->getInstitution(),
            'profile_completeness' => $profileCompleteness,
            'missing_items' => $missingItems,
            'kpis' => $kpis,
            'active_sessions' => $activeSessions,
            'recent_activity' => $recentActivity,
            'suggested_actions' => $suggestedActions,
            'student_inquiries_count' => 14,
            'pending_verifications_count' => 4,
            'documents_processed_count' => 842,
        ];
    }

    /**
     * 2. University Profile Management.
     */
    public function getProfile(): array
    {
        $sessionProfile = session()->get('uniguide_admin_profile', []);

        return array_merge($this->getInstitution(), $sessionProfile);
    }

    public function updateProfile(array $data): void
    {
        $current = $this->getProfile();
        $updated = array_merge($current, $data);
        session()->put('uniguide_admin_profile', $updated);
    }

    /**
     * 3. Campus Management.
     */
    public function getCampuses(): array
    {
        $default = [
            [
                'id' => 'cmp-1',
                'name' => 'Main Campus Islamabad (Sector H-12)',
                'city' => 'Islamabad',
                'province' => 'Islamabad (ICT)',
                'is_primary' => true,
                'established' => 2008,
                'land_area' => '707 Acres',
                'student_capacity' => 16500,
                'hostel_capacity' => '6,200 beds (22 Hostels)',
                'departments_count' => 7,
                'programs_count' => 32,
                'status' => 'active',
                'address' => 'Scholars Avenue, Sector H-12, Islamabad',
                'head' => 'Maj Gen (Retd) Muhammad Asim',
                'facilities' => ['Central Library', 'Olympiad Sports Complex', 'High-Performance Computing Cluster', 'Technology Incubation Center (NSTP)', 'Medical Center', 'Day Care'],
            ],
            [
                'id' => 'cmp-2',
                'name' => 'College of Electrical & Mechanical Engineering (CEME)',
                'city' => 'Rawalpindi',
                'province' => 'Punjab',
                'is_primary' => false,
                'established' => 1957,
                'land_area' => '110 Acres',
                'student_capacity' => 3200,
                'hostel_capacity' => '1,400 beds',
                'departments_count' => 4,
                'programs_count' => 8,
                'status' => 'active',
                'address' => 'Peshawar Road, Rawalpindi Cantonment',
                'head' => 'Brigadier Asif Iqbal',
                'facilities' => ['Robotics & Automation Labs', 'Wind Tunnel Testing Rig', 'Auditorium', 'Officers Mess', 'Sports Grounds'],
            ],
            [
                'id' => 'cmp-3',
                'name' => 'Military College of Signals (MCS)',
                'city' => 'Rawalpindi',
                'province' => 'Punjab',
                'is_primary' => false,
                'established' => 1947,
                'land_area' => '45 Acres',
                'student_capacity' => 1800,
                'hostel_capacity' => '850 beds',
                'departments_count' => 2,
                'programs_count' => 4,
                'status' => 'active',
                'address' => 'Humayun Road, Lalkurti, Rawalpindi',
                'head' => 'Brigadier Naveed Shafi',
                'facilities' => ['Cryptographic Research Labs', 'Fiber Optics Testbed', 'Smart Classrooms'],
            ],
            [
                'id' => 'cmp-4',
                'name' => 'Pakistan Navy Engineering College (PNEC)',
                'city' => 'Karachi',
                'province' => 'Sindh',
                'is_primary' => false,
                'established' => 1962,
                'land_area' => '55 Acres',
                'student_capacity' => 2100,
                'hostel_capacity' => '900 beds',
                'departments_count' => 3,
                'programs_count' => 6,
                'status' => 'active',
                'address' => 'Habib Rahmatullah Road, Karsaz, Karachi',
                'head' => 'Commodore Kamran Tariq SI(M)',
                'facilities' => ['Marine Propulsion Laboratory', 'Ship Hydrodynamics Basin', 'CAD/CAM Suites'],
            ],
        ];

        return session()->get('uniguide_admin_campuses', $default);
    }

    public function storeCampus(array $data): void
    {
        $campuses = $this->getCampuses();
        $id = 'cmp-'.(count($campuses) + 1);
        $data['id'] = $id;
        $data['is_primary'] = false;
        $data['status'] = 'active';
        $data['departments_count'] = 1;
        $data['programs_count'] = 2;
        $data['facilities'] = $data['facilities'] ?? ['Academic Block', 'Library', 'Hostel'];
        $campuses[] = $data;
        session()->put('uniguide_admin_campuses', $campuses);
    }

    public function updateCampus(string $id, array $data): void
    {
        $campuses = $this->getCampuses();
        foreach ($campuses as &$cmp) {
            if ($cmp['id'] === $id) {
                $cmp = array_merge($cmp, $data);
                break;
            }
        }
        session()->put('uniguide_admin_campuses', $campuses);
    }

    /**
     * 4. Department Management.
     */
    public function getDepartments(): array
    {
        $default = [
            [
                'id' => 'dept-1',
                'name' => 'School of Electrical Engineering & Computer Science (SEECS)',
                'acronym' => 'SEECS',
                'head' => 'Dr. Muhammad Ajmal Khan',
                'head_title' => 'Principal & Dean',
                'campus' => 'Main Campus Islamabad',
                'faculty_count' => 92,
                'phd_faculty_ratio' => '68% PhD Holders',
                'established' => 1999,
                'programs_count' => 8,
                'undergraduate_seats' => 450,
                'status' => 'active',
            ],
            [
                'id' => 'dept-2',
                'name' => 'School of Mechanical & Manufacturing Engineering (SMME)',
                'acronym' => 'SMME',
                'head' => 'Dr. Sohail Malik',
                'head_title' => 'Principal & Dean',
                'campus' => 'Main Campus Islamabad',
                'faculty_count' => 64,
                'phd_faculty_ratio' => '72% PhD Holders',
                'established' => 2008,
                'programs_count' => 5,
                'undergraduate_seats' => 280,
                'status' => 'active',
            ],
            [
                'id' => 'dept-3',
                'name' => 'NUST Business School (NBS)',
                'acronym' => 'NBS',
                'head' => 'Dr. Ayesha Farooq',
                'head_title' => 'Dean',
                'campus' => 'Main Campus Islamabad',
                'faculty_count' => 45,
                'phd_faculty_ratio' => '55% PhD Holders',
                'established' => 1999,
                'programs_count' => 6,
                'undergraduate_seats' => 320,
                'status' => 'active',
            ],
            [
                'id' => 'dept-4',
                'name' => 'School of Civil & Environmental Engineering (SCEE)',
                'acronym' => 'SCEE',
                'head' => 'Dr. Hamza Rafique',
                'head_title' => 'Principal & Dean',
                'campus' => 'Main Campus Islamabad',
                'faculty_count' => 58,
                'phd_faculty_ratio' => '65% PhD Holders',
                'established' => 2008,
                'programs_count' => 6,
                'undergraduate_seats' => 240,
                'status' => 'active',
            ],
            [
                'id' => 'dept-5',
                'name' => 'Atta-ur-Rahman School of Applied Biosciences (ASAB)',
                'acronym' => 'ASAB',
                'head' => 'Dr. Saadia Zahid',
                'head_title' => 'Principal & Dean',
                'campus' => 'Main Campus Islamabad',
                'faculty_count' => 38,
                'phd_faculty_ratio' => '80% PhD Holders',
                'established' => 2007,
                'programs_count' => 4,
                'undergraduate_seats' => 140,
                'status' => 'active',
            ],
        ];

        return session()->get('uniguide_admin_departments', $default);
    }

    public function storeDepartment(array $data): void
    {
        $depts = $this->getDepartments();
        $id = 'dept-'.(count($depts) + 1);
        $data['id'] = $id;
        $data['status'] = 'active';
        $data['programs_count'] = 1;
        $depts[] = $data;
        session()->put('uniguide_admin_departments', $depts);
    }

    /**
     * 5. Program Management.
     */
    public function getPrograms(): array
    {
        $default = [
            [
                'id' => 'prog-1',
                'name' => 'BS Computer Science',
                'slug' => 'bs-computer-science',
                'code' => 'NUST-SEECS-BSCS',
                'level' => 'Undergraduate',
                'department' => 'SEECS',
                'campus' => 'Islamabad Main Campus',
                'duration' => '4 Years (8 Semesters)',
                'credit_hours' => 134,
                'study_mode' => 'Full-time On-Campus',
                'accreditation' => 'NCEAC Category W',
                'intake_seats' => 140,
                'tuition_semester' => 'PKR 195,000',
                'tuition_numeric' => 195000,
                'min_eligibility' => '50% Intermediate / ICS / Pre-Medical with Math Remedial',
                'merit_formula' => '75% NET + 15% FSc + 10% Matric',
                'status' => 'published',
                'published_at' => '15 Jan 2026',
            ],
            [
                'id' => 'prog-2',
                'name' => 'BS Software Engineering',
                'slug' => 'bs-software-engineering',
                'code' => 'NUST-SEECS-BSSE',
                'level' => 'Undergraduate',
                'department' => 'SEECS',
                'campus' => 'Islamabad Main Campus',
                'duration' => '4 Years (8 Semesters)',
                'credit_hours' => 134,
                'study_mode' => 'Full-time On-Campus',
                'accreditation' => 'NCEAC Category W',
                'intake_seats' => 120,
                'tuition_semester' => 'PKR 195,000',
                'tuition_numeric' => 195000,
                'min_eligibility' => '50% Intermediate with Mathematics / Computer Science',
                'merit_formula' => '75% NET + 15% FSc + 10% Matric',
                'status' => 'published',
                'published_at' => '15 Jan 2026',
            ],
            [
                'id' => 'prog-3',
                'name' => 'BS Artificial Intelligence',
                'slug' => 'bs-artificial-intelligence',
                'code' => 'NUST-SEECS-BSAI',
                'level' => 'Undergraduate',
                'department' => 'SEECS',
                'campus' => 'Islamabad Main Campus',
                'duration' => '4 Years (8 Semesters)',
                'credit_hours' => 134,
                'study_mode' => 'Full-time On-Campus',
                'accreditation' => 'NCEAC Category W (Pending Final Audit)',
                'intake_seats' => 100,
                'tuition_semester' => 'PKR 195,000',
                'tuition_numeric' => 195000,
                'min_eligibility' => '50% Intermediate / Equivalent',
                'merit_formula' => '75% NET + 15% FSc + 10% Matric',
                'status' => 'published',
                'published_at' => '15 Jan 2026',
            ],
            [
                'id' => 'prog-4',
                'name' => 'BE Electrical Engineering',
                'slug' => 'be-electrical-engineering',
                'code' => 'NUST-SEECS-BEEE',
                'level' => 'Undergraduate',
                'department' => 'SEECS',
                'campus' => 'Islamabad Main Campus',
                'duration' => '4 Years (8 Semesters)',
                'credit_hours' => 136,
                'study_mode' => 'Full-time On-Campus',
                'accreditation' => 'PEC Level-II (Washington Accord)',
                'intake_seats' => 160,
                'tuition_semester' => 'PKR 185,000',
                'tuition_numeric' => 185000,
                'min_eligibility' => '60.0% FSc Pre-Engineering Mandated by PEC Act V of 1976',
                'merit_formula' => '75% NET + 15% FSc + 10% Matric',
                'status' => 'published',
                'published_at' => '15 Jan 2026',
            ],
            [
                'id' => 'prog-5',
                'name' => 'BE Mechanical Engineering',
                'slug' => 'be-mechanical-engineering',
                'code' => 'NUST-SMME-BEME',
                'level' => 'Undergraduate',
                'department' => 'SMME',
                'campus' => 'Islamabad Main Campus',
                'duration' => '4 Years (8 Semesters)',
                'credit_hours' => 138,
                'study_mode' => 'Full-time On-Campus',
                'accreditation' => 'PEC Level-II (Washington Accord)',
                'intake_seats' => 140,
                'tuition_semester' => 'PKR 185,000',
                'tuition_numeric' => 185000,
                'min_eligibility' => '60.0% FSc Pre-Engineering',
                'merit_formula' => '75% NET + 15% FSc + 10% Matric',
                'status' => 'published',
                'published_at' => '15 Jan 2026',
            ],
            [
                'id' => 'prog-6',
                'name' => 'Bachelor of Business Administration (BBA)',
                'slug' => 'bba-honors',
                'code' => 'NUST-NBS-BBA',
                'level' => 'Undergraduate',
                'department' => 'NBS',
                'campus' => 'Islamabad Main Campus',
                'duration' => '4 Years (8 Semesters)',
                'credit_hours' => 132,
                'study_mode' => 'Full-time On-Campus',
                'accreditation' => 'NBEAC W Category',
                'intake_seats' => 150,
                'tuition_semester' => 'PKR 210,000',
                'tuition_numeric' => 210000,
                'min_eligibility' => '50% Intermediate in any discipline',
                'merit_formula' => '75% NET (Business) + 15% FSc + 10% Matric',
                'status' => 'published',
                'published_at' => '15 Jan 2026',
            ],
            [
                'id' => 'prog-7',
                'name' => 'MS Data Science',
                'slug' => 'ms-data-science',
                'code' => 'NUST-SEECS-MSDS',
                'level' => 'Postgraduate',
                'department' => 'SEECS',
                'campus' => 'Islamabad Main Campus',
                'duration' => '2 Years (4 Semesters)',
                'credit_hours' => 30,
                'study_mode' => 'Evening / Weekend Hybrid',
                'accreditation' => 'HEC Recognized',
                'intake_seats' => 45,
                'tuition_semester' => 'PKR 125,000',
                'tuition_numeric' => 125000,
                'min_eligibility' => '16-Year BS CS/SE/EE with minimum 2.5/4.0 CGPA & GAT General',
                'merit_formula' => '50% Academic + 30% GAT + 20% Interview',
                'status' => 'published',
                'published_at' => '15 Jan 2026',
            ],
        ];

        return session()->get('uniguide_admin_programs', $default);
    }

    public function storeProgram(array $data): void
    {
        $programs = $this->getPrograms();
        $id = 'prog-'.(count($programs) + 1);
        $data['id'] = $id;
        $data['status'] = 'published';
        $data['published_at'] = now()->format('d M Y');
        $programs[] = $data;
        session()->put('uniguide_admin_programs', $programs);
    }

    public function updateProgram(string $id, array $data): void
    {
        $programs = $this->getPrograms();
        foreach ($programs as &$prog) {
            if ($prog['id'] === $id) {
                $prog = array_merge($prog, $data);
                break;
            }
        }
        session()->put('uniguide_admin_programs', $programs);
    }

    /**
     * 6. Fee Management.
     */
    public function getFeeSchedules(): array
    {
        $default = [
            'academic_categories' => [
                [
                    'category' => 'Undergraduate Computing & AI (SEECS)',
                    'tuition_per_semester' => 195000,
                    'lab_charges' => 15000,
                    'admission_fee_onetime' => 35000,
                    'security_deposit_refundable' => 25000,
                    'tuition_annual' => 390000,
                    'status' => 'Verified by Finance Directorate',
                    'updated_at' => '12 Feb 2026',
                ],
                [
                    'category' => 'Undergraduate Engineering (SEECS, SMME, SCEE)',
                    'tuition_per_semester' => 185000,
                    'lab_charges' => 20000,
                    'admission_fee_onetime' => 35000,
                    'security_deposit_refundable' => 25000,
                    'tuition_annual' => 370000,
                    'status' => 'Verified by Finance Directorate',
                    'updated_at' => '12 Feb 2026',
                ],
                [
                    'category' => 'Business & Management (NBS)',
                    'tuition_per_semester' => 210000,
                    'lab_charges' => 8000,
                    'admission_fee_onetime' => 35000,
                    'security_deposit_refundable' => 25000,
                    'tuition_annual' => 420000,
                    'status' => 'Verified by Finance Directorate',
                    'updated_at' => '12 Feb 2026',
                ],
                [
                    'category' => 'Postgraduate MS / MPhil (All Disciplines)',
                    'tuition_per_semester' => 125000,
                    'lab_charges' => 12000,
                    'admission_fee_onetime' => 30000,
                    'security_deposit_refundable' => 25000,
                    'tuition_annual' => 250000,
                    'status' => 'Verified by Finance Directorate',
                    'updated_at' => '12 Feb 2026',
                ],
            ],
            'hostel_tariffs' => [
                ['room_type' => 'Tri-Seater Room (Standard)', 'fee_per_semester' => 24000, 'mess_monthly' => 14500, 'security' => 15000],
                ['room_type' => 'Bi-Seater Room (Senior)', 'fee_per_semester' => 32000, 'mess_monthly' => 14500, 'security' => 15000],
                ['room_type' => 'Single Occupancy (Postgraduate / Scholars)', 'fee_per_semester' => 45000, 'mess_monthly' => 14500, 'security' => 15000],
            ],
            'policies' => [
                'installment_allowed' => true,
                'installment_terms' => '2 Equal Installments per semester on formal application to DDA',
                'refund_policy' => '100% refund up to 7th day of classes; 50% refund up to 15th day as per HEC National Policy',
                'fee_gazette_ref' => 'NUST/FIN/NOTIF/UG-F26/014',
            ],
        ];

        return session()->get('uniguide_admin_fees', $default);
    }

    public function updateFees(array $data): void
    {
        $fees = $this->getFeeSchedules();
        if (isset($data['category_index']) && isset($data['tuition'])) {
            $idx = intval($data['category_index']);
            if (isset($fees['academic_categories'][$idx])) {
                $fees['academic_categories'][$idx]['tuition_per_semester'] = floatval($data['tuition']);
                $fees['academic_categories'][$idx]['tuition_annual'] = floatval($data['tuition']) * 2;
                $fees['academic_categories'][$idx]['updated_at'] = now()->format('d M Y');
            }
        }
        session()->put('uniguide_admin_fees', $fees);
    }

    /**
     * 7. Admission Cycles & Management.
     */
    public function getAdmissionCycles(): array
    {
        $default = [
            [
                'id' => 'adm-1',
                'name' => 'Fall 2026 Undergraduate Admissions',
                'cycle' => 'Fall 2026',
                'level' => 'Undergraduate',
                'status' => 'open',
                'status_badge' => 'Applications Open',
                'badge_color' => 'success',
                'portal_open_date' => '01 May 2026',
                'deadline_date' => '15 July 2026',
                'entry_tests' => 'NET-4 Series (Islamabad, Karachi, Quetta)',
                'merit_list_1' => '20 July 2026',
                'merit_list_2' => '27 July 2026',
                'orientation_date' => '01 September 2026',
                'total_seats' => 3450,
                'applications_received' => 12840,
                'quotas' => [
                    ['category' => 'Open Merit', 'percentage' => 85, 'seats' => 2932],
                    ['category' => 'Balochistan & Erstwhile FATA Quota', 'percentage' => 5, 'seats' => 172],
                    ['category' => 'Armed Forces Wards (Army/Navy/PAF)', 'percentage' => 5, 'seats' => 172],
                    ['category' => 'International / Overseas Pakistani (ACT/SAT)', 'percentage' => 5, 'seats' => 174],
                ],
            ],
            [
                'id' => 'adm-2',
                'name' => 'Fall 2026 Postgraduate Admissions',
                'cycle' => 'Fall 2026',
                'level' => 'Postgraduate (MS/PhD)',
                'status' => 'open',
                'status_badge' => 'Applications Open',
                'badge_color' => 'success',
                'portal_open_date' => '15 May 2026',
                'deadline_date' => '25 July 2026',
                'entry_tests' => 'GAT Subject & G-NET Computer Based',
                'merit_list_1' => '05 August 2026',
                'merit_list_2' => '12 August 2026',
                'orientation_date' => '01 September 2026',
                'total_seats' => 1200,
                'applications_received' => 3890,
                'quotas' => [
                    ['category' => 'Open Merit', 'percentage' => 90, 'seats' => 1080],
                    ['category' => 'HEC Indigenous Scholars Quota', 'percentage' => 10, 'seats' => 120],
                ],
            ],
            [
                'id' => 'adm-3',
                'name' => 'Spring 2027 Transfer & Special Intake',
                'cycle' => 'Spring 2027',
                'level' => 'Undergraduate Migration',
                'status' => 'upcoming',
                'status_badge' => 'Upcoming (November 2026)',
                'badge_color' => 'neutral',
                'portal_open_date' => '15 November 2026',
                'deadline_date' => '20 December 2026',
                'entry_tests' => 'Departmental Migration Assessment',
                'merit_list_1' => '05 January 2027',
                'merit_list_2' => '12 January 2027',
                'orientation_date' => '01 February 2027',
                'total_seats' => 120,
                'applications_received' => 0,
                'quotas' => [
                    ['category' => 'Lateral Entry / Credit Transfer', 'percentage' => 100, 'seats' => 120],
                ],
            ],
        ];

        return session()->get('uniguide_admin_admissions', $default);
    }

    public function updateAdmissionCycle(string $id, array $data): void
    {
        $cycles = $this->getAdmissionCycles();
        foreach ($cycles as &$cyc) {
            if ($cyc['id'] === $id) {
                $cyc = array_merge($cyc, $data);
                break;
            }
        }
        session()->put('uniguide_admin_admissions', $cycles);
    }

    /**
     * 8. Eligibility Rules Management.
     */
    public function getEligibilityRules(): array
    {
        $default = [
            'statutory_enforcements' => [
                [
                    'id' => 'rule-pec-eng',
                    'title' => 'PEC 60% Pre-Engineering Statutory Minimum',
                    'authority' => 'Pakistan Engineering Council (PEC)',
                    'reference' => 'PEC Act No. V of 1976 amended 2026',
                    'threshold' => '60.0% in HSSC Pre-Engineering or IBCC Equivalence',
                    'scope' => 'All Bachelor of Engineering (BE) programs',
                    'lock_status' => 'Statutory Hard Lock (Cannot be reduced below 60%)',
                    'is_enforced' => true,
                ],
                [
                    'id' => 'rule-nceac-cs',
                    'title' => 'NCEAC 50% Computing Baseline Rule',
                    'authority' => 'National Computing Education Accreditation Council (NCEAC)',
                    'reference' => 'NCEAC Accreditation Regulations 2025-2029',
                    'threshold' => '50.0% in Intermediate / HSSC with Mathematics',
                    'scope' => 'BS Computer Science, BS Software Engineering, BS AI',
                    'lock_status' => 'Statutory Hard Lock (Cannot be reduced below 50%)',
                    'is_enforced' => true,
                ],
                [
                    'id' => 'rule-hec-premed',
                    'title' => 'HEC Pre-Medical to Computing Transition Directive',
                    'authority' => 'Higher Education Commission (HEC)',
                    'reference' => 'Gazette SRO 45(I)/2026',
                    'threshold' => '50.0% Pre-Medical + 6 Credit Hours Year-1 Deficiency Math',
                    'scope' => 'All BS Computing Programs',
                    'lock_status' => 'Statutory National Policy',
                    'is_enforced' => true,
                ],
            ],
            'merit_weightage_formula' => [
                'net_test_weight' => 75,
                'fsc_hssc_weight' => 15,
                'matric_ssc_weight' => 10,
                'sat_act_equivalent_allowed' => true,
                'last_amended' => 'Academic Council Resolution 12/2025',
            ],
        ];

        return session()->get('uniguide_admin_eligibility', $default);
    }

    public function updateEligibilityRules(array $data): void
    {
        $rules = $this->getEligibilityRules();
        if (isset($data['net_weight'])) {
            $rules['merit_weightage_formula']['net_test_weight'] = intval($data['net_weight']);
            $rules['merit_weightage_formula']['fsc_hssc_weight'] = intval($data['fsc_weight']);
            $rules['merit_weightage_formula']['matric_ssc_weight'] = intval($data['matric_weight']);
            $rules['merit_weightage_formula']['last_amended'] = now()->format('d M Y').' by Admissions Directorate';
        }
        session()->put('uniguide_admin_eligibility', $rules);
    }

    /**
     * 9. Scholarship Management.
     */
    public function getScholarships(): array
    {
        $default = [
            [
                'id' => 'sch-1',
                'name' => 'HEC Need-Based Financial Aid Scheme',
                'funding_agency' => 'Higher Education Commission Pakistan',
                'category' => 'Need-Based',
                'coverage' => '100% Tuition Fee + PKR 6,000 / month stipend',
                'annual_budget' => 'PKR 45,000,000',
                'beneficiaries_target' => 180,
                'awarded_count' => 162,
                'eligibility_criteria' => 'Monthly household income below PKR 65,000 with attested documentation',
                'application_window' => '01 May 2026 to 25 July 2026',
                'status' => 'active',
            ],
            [
                'id' => 'sch-2',
                'name' => 'NUST Internal Merit Concession Scheme',
                'funding_agency' => 'NUST Endowment Fund Trust',
                'category' => 'Merit-Based',
                'coverage' => '100% Tuition for Top 10 NET Ranks; 50% for next 15 Ranks',
                'annual_budget' => 'PKR 28,000,000',
                'beneficiaries_target' => 50,
                'awarded_count' => 48,
                'eligibility_criteria' => 'Top 25 Positions in aggregate merit merit list',
                'application_window' => 'Automatic Evaluation via Merit Scorecard',
                'status' => 'active',
            ],
            [
                'id' => 'sch-3',
                'name' => 'Ehsaas / BISP Undergraduate Scholarship Initiative',
                'funding_agency' => 'Benazir Income Support Programme / HEC',
                'category' => 'Need & Social Safety',
                'coverage' => 'Full Tuition Fee + PKR 4,000 / month transport subsidy',
                'annual_budget' => 'PKR 32,000,000',
                'beneficiaries_target' => 95,
                'awarded_count' => 88,
                'eligibility_criteria' => 'BISP registered beneficiaries with Scorecard verification',
                'application_window' => '15 June 2026 to 15 August 2026',
                'status' => 'active',
            ],
            [
                'id' => 'sch-4',
                'name' => 'Punjab & Balochistan Provincial Educational Endowments (PEEF/BEEF)',
                'funding_agency' => 'Provincial Endowment Boards',
                'category' => 'Provincial Quota Endowment',
                'coverage' => 'Full Tuition + Dedicated On-Campus Hostel Accommodation',
                'annual_budget' => 'PKR 22,500,000',
                'beneficiaries_target' => 65,
                'awarded_count' => 61,
                'eligibility_criteria' => 'Domiciled in designated underdeveloped districts with 60%+ marks',
                'application_window' => 'Annual Directorate Intake',
                'status' => 'active',
            ],
        ];

        return session()->get('uniguide_admin_scholarships', $default);
    }

    public function storeScholarship(array $data): void
    {
        $schs = $this->getScholarships();
        $id = 'sch-'.(count($schs) + 1);
        $data['id'] = $id;
        $data['status'] = 'active';
        $data['awarded_count'] = 0;
        $schs[] = $data;
        session()->put('uniguide_admin_scholarships', $schs);
    }

    /**
     * 10. Document Management & Verification.
     */
    public function getDocuments(): array
    {
        $default = [
            'institutional_documents' => [
                [
                    'id' => 'inst-doc-1',
                    'title' => 'NUST Undergraduate Prospectus 2026-2027 (Official Approved)',
                    'type' => 'prospectus',
                    'file_name' => 'nust_prospectus_ug_2026_v4.pdf',
                    'file_size' => '24.8 MB',
                    'uploaded_by' => 'Dr. Tariq Mahmood (Admissions Directorate)',
                    'uploaded_at' => '15 April 2026',
                    'verification_status' => 'verified',
                    'verification_badge' => 'Statutory Approved',
                ],
                [
                    'id' => 'inst-doc-2',
                    'title' => 'PEC Level-II Washington Accord Re-accreditation Gazette',
                    'type' => 'gazette',
                    'file_name' => 'pec_reaccreditation_notif_2026_seecs.pdf',
                    'file_size' => '3.2 MB',
                    'uploaded_by' => 'Prof. Naveed Arshad (Registrar Office)',
                    'uploaded_at' => '10 January 2026',
                    'verification_status' => 'verified',
                    'verification_badge' => 'PEC Verified',
                ],
                [
                    'id' => 'inst-doc-3',
                    'title' => 'Tuition Fee Schedule Gazette & Hostel Tariffs Fall 2026',
                    'type' => 'fee_gazette',
                    'file_name' => 'fee_schedule_notif_f26_d7.pdf',
                    'file_size' => '1.8 MB',
                    'uploaded_by' => 'Finance Directorate',
                    'uploaded_at' => '12 Feb 2026',
                    'verification_status' => 'verified',
                    'verification_badge' => 'Finance Approved',
                ],
            ],
            'candidate_submissions' => [
                [
                    'id' => 'cand-doc-1',
                    'applicant_name' => 'Bilal Ahmed',
                    'applicant_id' => 'NUST-2026-UG-1148',
                    'program' => 'BS Computer Science',
                    'doc_name' => 'HSSC / FSc Official Sanad (FBISE Attested)',
                    'status' => 'pending',
                    'status_label' => 'Awaiting Verification',
                    'submitted_at' => '02 Nov 2026',
                    'file_url' => '#',
                ],
                [
                    'id' => 'cand-doc-2',
                    'applicant_name' => 'Fatima Noor',
                    'applicant_id' => 'NUST-2026-UG-2041',
                    'program' => 'BS Software Engineering',
                    'doc_name' => 'IBCC Cambridge A-Level Equivalence Certificate',
                    'status' => 'verified',
                    'status_label' => 'Verified & Audited',
                    'submitted_at' => '01 Nov 2026',
                    'file_url' => '#',
                ],
                [
                    'id' => 'cand-doc-3',
                    'applicant_name' => 'Muhammad Usman',
                    'applicant_id' => 'NUST-2026-UG-0914',
                    'program' => 'BE Electrical Engineering',
                    'doc_name' => 'Father Income Tax Return & Salary Affidavit',
                    'status' => 'flagged',
                    'status_label' => 'Flagged for Discrepancy',
                    'submitted_at' => '28 Oct 2026',
                    'file_url' => '#',
                ],
            ],
        ];

        return session()->get('uniguide_admin_documents', $default);
    }

    public function verifyDocument(string $id, string $status): void
    {
        $docs = $this->getDocuments();
        foreach ($docs['candidate_submissions'] as &$sub) {
            if ($sub['id'] === $id) {
                $sub['status'] = $status;
                $sub['status_label'] = match ($status) {
                    'verified' => 'Verified & Audited',
                    'flagged' => 'Flagged for Discrepancy',
                    default => 'Pending Verification',
                };
                break;
            }
        }
        session()->put('uniguide_admin_documents', $docs);
    }

    /**
     * 11. AI Data Verification (Source Review Experience).
     */
    public function getAiVerificationQueue(): array
    {
        $default = [
            [
                'id' => 'ai-item-1',
                'entity' => 'Tuition Fee Schedule',
                'field' => 'BS Artificial Intelligence Semester Fee',
                'field_key' => 'tuition_per_semester',
                'source_title' => 'NUST Undergraduate Prospectus 2026-2027 (Page 42)',
                'source_url' => 'https://nust.edu.pk/admissions/fees-2026.pdf',
                'source_text_snippet' => 'The revised undergraduate tuition fee for Computing and Artificial Intelligence programs (BS CS, BS SE, BS AI) admitted in Fall 2026 shall be PKR 195,000 per semester.',
                'extraction_timestamp' => '2026-11-02 09:15 PKT',
                'previous_value' => 'PKR 180,000',
                'proposed_value' => 'PKR 195,000',
                'value_diff' => '+PKR 15,000 (+8.3%)',
                'confidence_score' => 98.4,
                'validation_warning' => 'Fee adjustment of +8.3% detected. Complies with HEC Senate inflation ceiling guidelines.',
                'has_warning' => false,
                'status' => 'pending', // pending, approved, rejected, edited
            ],
            [
                'id' => 'ai-item-2',
                'entity' => 'Curriculum Specification',
                'field' => 'BE Mechatronics Total Degree Credit Hours',
                'field_key' => 'credit_hours',
                'source_title' => 'PEC Outcome-Based Education (OBE) Gazette Re-accreditation 2026',
                'source_url' => 'https://pec.org.pk/gazette/nust-ceme-mechatronics-2026.pdf',
                'source_text_snippet' => 'In accordance with the Washington Accord Level-II revised OBE manual, the total graduation credit hours for BE Mechatronics are rationalized from 138 to 134 credit hours.',
                'extraction_timestamp' => '2026-11-03 14:22 PKT',
                'previous_value' => '138 Credit Hours',
                'proposed_value' => '134 Credit Hours',
                'value_diff' => '-4 Credit Hours',
                'confidence_score' => 99.1,
                'validation_warning' => null,
                'has_warning' => false,
                'status' => 'pending',
            ],
            [
                'id' => 'ai-item-3',
                'entity' => 'Admission Cycle Timeline',
                'field' => 'NET-4 Series Online Registration Deadline',
                'field_key' => 'application_deadline',
                'source_title' => 'NUST Admissions Directorate Public Circular No. 18',
                'source_url' => 'https://ugadmissions.nust.edu.pk/deadlines',
                'source_text_snippet' => 'To facilitate candidates awaiting FBISE and provincial board examination results, the last date for online application submission for NET-4 has been extended from 05 July to 15 July 2026.',
                'extraction_timestamp' => '2026-11-04 08:30 PKT',
                'previous_value' => '05 July 2026',
                'proposed_value' => '15 July 2026',
                'value_diff' => '+10 Days Extension',
                'confidence_score' => 97.8,
                'validation_warning' => 'Extension affects Merit List announcement calendar. Requires syncing with IT test scheduling engine.',
                'has_warning' => true,
                'status' => 'pending',
            ],
            [
                'id' => 'ai-item-4',
                'entity' => 'Hostel Accommodation Tariff',
                'field' => 'Internal Tri-Seater Hostel Mess Charges',
                'field_key' => 'hostel_mess_monthly',
                'source_title' => 'Hostels Directorate Notification HD/26/MESS',
                'source_url' => 'https://nust.edu.pk/hostels/tariff-f26.pdf',
                'source_text_snippet' => 'Monthly dining hall mess charges across all internal undergraduate hostels in Sector H-12 are fixed at PKR 14,500 effective from Fall 2026 semester.',
                'extraction_timestamp' => '2026-11-01 11:40 PKT',
                'previous_value' => 'PKR 13,000 / month',
                'proposed_value' => 'PKR 14,500 / month',
                'value_diff' => '+PKR 1,500 / month',
                'confidence_score' => 96.2,
                'validation_warning' => null,
                'has_warning' => false,
                'status' => 'pending',
            ],
        ];

        return session()->get('uniguide_admin_ai_queue', $default);
    }

    public function processAiVerification(string $id, string $action, ?string $editedValue = null): void
    {
        $queue = $this->getAiVerificationQueue();
        foreach ($queue as &$item) {
            if ($item['id'] === $id) {
                $item['status'] = $action;
                if ($action === 'edited' && $editedValue !== null) {
                    $item['proposed_value'] = $editedValue;
                }
                $item['reviewed_at'] = now()->format('d M Y H:i');
                $item['reviewed_by'] = 'Dr. Tariq Mahmood';
                break;
            }
        }
        session()->put('uniguide_admin_ai_queue', $queue);
    }

    /**
     * 12. Student Inquiries Management.
     */
    public function getInquiries(): array
    {
        $default = [
            [
                'id' => 'inq-1',
                'candidate_name' => 'Bilal Ahmed Raza',
                'candidate_email' => 'bilal.raza@gmail.com',
                'candidate_phone' => '+92 301 5554128',
                'qualification' => 'FSc Pre-Medical (82.4% FBISE)',
                'target_program' => 'BS Software Engineering',
                'subject' => 'Eligibility under HEC Pre-Med to Computing Policy SRO 45(I)/2026',
                'message' => 'Respected Admissions Team, I have 82.4% in FSc Pre-Medical. Can I apply for BS Software Engineering at NUST SEECS in Fall 2026 without appearing in extra mathematics in FSc? Does NUST offer the 2 remedial mathematics courses in Year-1?',
                'status' => 'new', // new, in_progress, resolved
                'priority' => 'high',
                'received_at' => 'Today at 10:14 AM',
                'assigned_to' => 'Admissions Desk (Engr. Farhan)',
                'replies' => [],
            ],
            [
                'id' => 'inq-2',
                'candidate_name' => 'Fatima Noor Alvi',
                'candidate_email' => 'fatima.alvi@outlook.com',
                'candidate_phone' => '+92 321 9988112',
                'qualification' => 'Cambridge A-Levels (3 A*s: Math, Physics, Chem)',
                'target_program' => 'BE Electrical Engineering',
                'subject' => 'IBCC Equivalence Certificate Submission Timeline',
                'message' => 'My Cambridge A2 results will be declared on 12 August 2026. Can I sit for NET-4 with my AS-Level IBCC certificate and submit final equivalence before the 2nd merit list closes?',
                'status' => 'in_progress',
                'priority' => 'normal',
                'received_at' => 'Yesterday at 04:30 PM',
                'assigned_to' => 'Dr. Tariq Mahmood',
                'replies' => [
                    [
                        'sender' => 'Dr. Tariq Mahmood (Admissions Director)',
                        'timestamp' => 'Yesterday at 06:15 PM',
                        'body' => 'Dear Fatima, Yes, provisional admission is granted based on your AS-level IBCC equivalence. You must provide an undertaking to submit the final certificate by 30 August 2026.',
                    ],
                ],
            ],
            [
                'id' => 'inq-3',
                'candidate_name' => 'Muhammad Usman Zafar',
                'candidate_email' => 'usman.zafar@yahoo.com',
                'candidate_phone' => '+92 333 4455221',
                'qualification' => 'FSc Pre-Engineering (78.0% BISE Lahore)',
                'target_program' => 'BS Computer Science',
                'subject' => 'HEC Need-Based Aid for Internal Hostel Charges',
                'message' => 'Does the HEC Need-Based scholarship cover on-campus hostel accommodation and mess charges or only tuition fee?',
                'status' => 'resolved',
                'priority' => 'normal',
                'received_at' => '02 Nov 2026',
                'assigned_to' => 'Ms. Rubina Akhtar (Financial Aid)',
                'replies' => [
                    [
                        'sender' => 'Ms. Rubina Akhtar (Financial Aid Office)',
                        'timestamp' => '02 Nov 2026 at 03:00 PM',
                        'body' => 'Dear Usman, The HEC Need-Based Aid covers 100% tuition plus a monthly stipend of PKR 6,000 which can be utilized towards your hostel dining charges.',
                    ],
                ],
            ],
            [
                'id' => 'inq-4',
                'candidate_name' => 'Zainab Binte Khalid',
                'candidate_email' => 'zainab.khalid@gmail.com',
                'candidate_phone' => '+92 345 7766110',
                'qualification' => 'ICS Computer Science (88.0% FBISE)',
                'target_program' => 'BS Artificial Intelligence',
                'subject' => 'NET-4 Test Center Change Request',
                'message' => 'I selected Karachi center while registering, but I will be in Islamabad during July. Can my exam slot be transferred to Sector H-12 Islamabad campus?',
                'status' => 'resolved',
                'priority' => 'low',
                'received_at' => '01 Nov 2026',
                'assigned_to' => 'IT Exam Operations',
                'replies' => [
                    [
                        'sender' => 'IT Exam Desk',
                        'timestamp' => '01 Nov 2026 at 05:20 PM',
                        'body' => 'Center updated successfully. Your new admit slip is ready for download in your applicant portal.',
                    ],
                ],
            ],
        ];

        return session()->get('uniguide_admin_inquiries', $default);
    }

    public function replyInquiry(string $id, string $replyText): void
    {
        $inquiries = $this->getInquiries();
        foreach ($inquiries as &$inq) {
            if ($inq['id'] === $id) {
                $inq['replies'][] = [
                    'sender' => 'Dr. Tariq Mahmood (Admissions Director)',
                    'timestamp' => now()->format('d M Y \a\t h:i A'),
                    'body' => $replyText,
                ];
                $inq['status'] = 'resolved';
                break;
            }
        }
        session()->put('uniguide_admin_inquiries', $inquiries);
    }

    public function updateInquiryStatus(string $id, string $status): void
    {
        $inquiries = $this->getInquiries();
        foreach ($inquiries as &$inq) {
            if ($inq['id'] === $id) {
                $inq['status'] = $status;
                break;
            }
        }
        session()->put('uniguide_admin_inquiries', $inquiries);
    }

    /**
     * 13. Staff Roster & System Settings.
     */
    public function getStaffAndSettings(): array
    {
        $defaultStaff = [
            [
                'id' => 'stf-1',
                'name' => 'Dr. Tariq Mahmood',
                'email' => 'tariq.mahmood@nust.edu.pk',
                'role' => 'Director Admissions',
                'role_badge' => 'Institutional Super Admin',
                'department' => 'Admissions Directorate',
                'status' => 'active',
                'two_factor_enabled' => true,
                'last_active' => 'Active now',
                'permissions' => ['all'],
            ],
            [
                'id' => 'stf-2',
                'name' => 'Prof. Naveed Arshad',
                'email' => 'registrar@nust.edu.pk',
                'role' => 'Registrar Office',
                'role_badge' => 'HEC Compliance Admin',
                'department' => 'Central Registry',
                'status' => 'active',
                'two_factor_enabled' => true,
                'last_active' => '3 hours ago',
                'permissions' => ['profile', 'charter', 'accreditations', 'audits'],
            ],
            [
                'id' => 'stf-3',
                'name' => 'Engr. Farhan Qureshi',
                'email' => 'farhan.q@nust.edu.pk',
                'role' => 'Senior Admissions Officer',
                'role_badge' => 'Program & Cycles Officer',
                'department' => 'Admissions Operations',
                'status' => 'active',
                'two_factor_enabled' => true,
                'last_active' => 'Yesterday',
                'permissions' => ['programs', 'admissions', 'inquiries'],
            ],
            [
                'id' => 'stf-4',
                'name' => 'Ms. Rubina Akhtar',
                'email' => 'financial.aid@nust.edu.pk',
                'role' => 'Assistant Director Financial Aid',
                'role_badge' => 'Scholarships Admin',
                'department' => 'Financial Aid Directorate',
                'status' => 'active',
                'two_factor_enabled' => false,
                'last_active' => '2 days ago',
                'permissions' => ['scholarships', 'need_based_aid'],
            ],
        ];

        $defaultSettings = [
            'two_factor_required' => true,
            'ip_whitelist_enabled' => false,
            'whitelisted_ips' => '111.68.96.0/24, 202.83.172.0/24',
            'auto_ai_scrape_notification' => true,
            'weekly_inquiry_digest' => true,
            'notify_email' => 'admissions.alerts@nust.edu.pk',
            'api_token_generated' => true,
            'api_access_scope' => 'read:programs, read:fees, write:merit_lists',
        ];

        $auditLog = [
            ['timestamp' => '2026-11-04 11:20 PKT', 'user' => 'Dr. Tariq Mahmood', 'action' => 'Updated NET-4 registration extension date', 'ip' => '111.68.96.14'],
            ['timestamp' => '2026-11-03 16:45 PKT', 'user' => 'Ms. Rubina Akhtar', 'action' => 'Approved 18 HEC Need-Based candidate dossiers', 'ip' => '111.68.96.22'],
            ['timestamp' => '2026-11-02 09:10 PKT', 'user' => 'Prof. Naveed Arshad', 'action' => 'Re-certified PEC Washington Accord Level-II standing', 'ip' => '111.68.96.05'],
            ['timestamp' => '2026-10-31 14:02 PKT', 'user' => 'Engr. Farhan Qureshi', 'action' => 'Added BS Artificial Intelligence 100-seat intake allocation', 'ip' => '111.68.96.31'],
        ];

        return [
            'staff' => session()->get('uniguide_admin_staff', $defaultStaff),
            'settings' => session()->get('uniguide_admin_settings', $defaultSettings),
            'audit_log' => $auditLog,
        ];
    }

    public function updateSettings(array $data): void
    {
        $current = $this->getStaffAndSettings();
        $updated = array_merge($current['settings'], $data);
        session()->put('uniguide_admin_settings', $updated);
    }
}
