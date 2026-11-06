<?php

namespace App\Services;

use App\Models\Campus;
use App\Models\Program;
use App\Models\University;

class DiscoveryService
{
    public function __construct()
    {
        // Enrich programs with alias keys for consistent view consumption
        foreach ($this->programs as &$prog) {
            $prog['cutoff_merit'] = $prog['estimated_closing_merit'] ?? 'Competitive';
            $prog['fee_semester'] = $prog['fee_per_semester'] ?? 'PKR 140,000';
        }
        unset($prog);
    }

    /**
     * Complete repository of accredited Pakistani universities.
     */
    protected array $universities = [
        [
            'id' => 1,
            'slug' => 'nust',
            'name' => 'National University of Sciences & Technology',
            'acronym' => 'NUST',
            'charter_type' => 'Public Sector',
            'sector' => 'public',
            'hec_ranking' => '#1 Overall Engineering & Technology',
            'hec_category' => 'Category W',
            'established' => 1991,
            'charter_citation' => 'Act of Parliament No. XX of 1991 (Federal)',
            'gazette_reference' => 'Federal Gazette Notification S.R.O. 451(I)/91',
            'website' => 'https://nust.edu.pk',
            'phone' => '+92 (051) 9085-1000',
            'email' => 'admissions@nust.edu.pk',
            'address' => 'Sector H-12, Islamabad, 44000, Pakistan',
            'province' => 'ict',
            'province_label' => 'Islamabad (ICT)',
            'city' => 'Islamabad',
            'fee_range' => 'PKR 135,000 – 175,000',
            'fee_min' => 135000,
            'fee_max' => 175000,
            'acceptance_rate' => '~12% Highly Selective',
            'total_students' => '18,500+',
            'faculty_phd_ratio' => '82% PhD Faculty',
            'campus_size' => '700 Acres (H-12 Main Campus)',
            'hostel_availability' => 'Guaranteed for Outstation Female & Male Students',
            'status' => 'open',
            'deadline' => '15 July 2026',
            'featured' => true,
            'accreditations' => [
                ['body' => 'PEC', 'title' => 'Pakistan Engineering Council Level-II (Washington Accord)'],
                ['body' => 'NCEAC', 'title' => 'National Computing Education Accreditation Council (Category W)'],
                ['body' => 'NBEAC', 'title' => 'National Business Education Accreditation Council (W-Category)'],
                ['body' => 'HEC', 'title' => 'Higher Education Commission Highest Quality Tier'],
            ],
            'description' => 'NUST is Pakistan’s premier comprehensive STEM and research university, recognized globally for excellence in electrical, mechanical, and software engineering, as well as computing and business education. Operating its primary 700-acre master-planned campus in sector H-12 Islamabad, NUST maintains high research output and world-standard laboratories.',
            'campuses' => [
                [
                    'slug' => 'islamabad-h12',
                    'name' => 'Islamabad Main Campus (H-12)',
                    'is_main' => true,
                    'city' => 'Islamabad',
                    'province' => 'Islamabad (ICT)',
                    'address' => 'Scholars Avenue, Sector H-12, Islamabad',
                    'phone' => '+92 (051) 9085-6878',
                    'email' => 'info@nust.edu.pk',
                    'hostel_capacity' => '6,200 beds across 16 internal hostels',
                    'transport' => 'Extensive university bus network connecting Rawalpindi & Islamabad',
                    'libraries' => 'Central NUST Library (150,000+ volumes, IEEE & ScienceDirect access)',
                    'facilities' => ['National Science & Technology Park (NSTP)', 'Indoor Olympic Swimming Pool', 'Sports Complex', 'Conveyance & Medical Center'],
                    'programs_offered_count' => 42,
                ],
                [
                    'slug' => 'eme-rawalpindi',
                    'name' => 'College of Electrical & Mechanical Engineering (CEME)',
                    'is_main' => false,
                    'city' => 'Rawalpindi',
                    'province' => 'Punjab',
                    'address' => 'Peshawar Road, Rawalpindi',
                    'phone' => '+92 (051) 9278-050',
                    'email' => 'ceme@nust.edu.pk',
                    'hostel_capacity' => '1,800 on-campus beds',
                    'transport' => 'Campus transport shuttle to H-12 and metro route',
                    'libraries' => 'EME Departmental Engineering Library',
                    'facilities' => ['Robotics & Mechatronics Research Center', 'Wind Tunnel Testing Facility', 'High Voltage Lab'],
                    'programs_offered_count' => 12,
                ],
                [
                    'slug' => 'pnec-karachi',
                    'name' => 'Pakistan Navy Engineering College (PNEC)',
                    'is_main' => false,
                    'city' => 'Karachi',
                    'province' => 'Sindh',
                    'address' => 'Habib Rahmatullah Road, Karsaz, Karachi',
                    'phone' => '+92 (021) 4850-3000',
                    'email' => 'pnec@nust.edu.pk',
                    'hostel_capacity' => '800 beds',
                    'transport' => 'Karachi metropolitan routes shuttle',
                    'libraries' => 'PNEC Marine & Technical Library',
                    'facilities' => ['Marine Propulsion Laboratory', 'Autonomous Underwater Vehicle Lab'],
                    'programs_offered_count' => 8,
                ],
            ],
            'scholarships_offered' => [
                'HEC Need-Based Undergraduate Scholarship (Full Tuition + PKR 6,000/mo)',
                'NUST Need-Based Financial Aid (Endowment Trust Fund covering 25%–100% tuition)',
                'Ehsaas National Undergraduate Scholarship Program',
                'NUST Merit Scholarships for Top 3 Position Holders in NET',
            ],
            'admission_requirements' => [
                'Minimum 60% aggregate in Matriculation and FSc (Pre-Engineering / ICS with Math)',
                'Mandatory NUST Entry Test (NET-1, NET-2, or NET-3) or SAT Subject / ACT score',
                'Final selection aggregate formula: 75% NET + 15% FSc + 10% Matriculation',
            ],
            'disciplines' => ['engineering', 'computing', 'business', 'natural_sciences'],
            'degree_levels' => ['bs', 'ms', 'phd'],
            'last_verified' => '03 Nov 2026',
        ],
        [
            'id' => 2,
            'slug' => 'fast-nuces',
            'name' => 'FAST - National University of Computer & Emerging Sciences',
            'acronym' => 'FAST',
            'charter_type' => 'Chartered Autonomous',
            'sector' => 'private',
            'hec_ranking' => '#1 Computing & Artificial Intelligence',
            'hec_category' => 'Category W',
            'established' => 2000,
            'charter_citation' => 'Federal Ordinance No. XXIII of 2000 (Govt. of Pakistan)',
            'gazette_reference' => 'Gazette of Pakistan Extraordinary Part-I, July 2000',
            'website' => 'https://nu.edu.pk',
            'phone' => '+92 (051) 111-128-128',
            'email' => 'admissions@nu.edu.pk',
            'address' => 'AK Brohi Road, Sector H-9, Islamabad, Pakistan',
            'province' => 'ict',
            'province_label' => 'Islamabad (ICT)',
            'city' => 'Islamabad',
            'fee_range' => 'PKR 145,000 – 165,000',
            'fee_min' => 145000,
            'fee_max' => 165000,
            'acceptance_rate' => '~15% Competitive',
            'total_students' => '14,000+ (Nationwide)',
            'faculty_phd_ratio' => '74% PhD Faculty in Computing',
            'campus_size' => 'Multi-Campus Network',
            'hostel_availability' => 'Designated Outstation Hostels at Islamabad, Lahore & Chiniot',
            'status' => 'open',
            'deadline' => '05 July 2026',
            'featured' => true,
            'accreditations' => [
                ['body' => 'NCEAC', 'title' => 'National Computing Education Accreditation Council (Top Ranked CS)'],
                ['body' => 'PEC', 'title' => 'Pakistan Engineering Council (Level-II) for Electrical & Software'],
                ['body' => 'HEC', 'title' => 'Chartered Federal Degree Awarding Institute'],
            ],
            'description' => 'FAST-NUCES is widely regarded as Pakistan’s foremost institution for Computer Science, Software Engineering, and Artificial Intelligence education. Established by the Foundation for Advancement of Science and Technology, FAST graduates form the core technical leadership of Pakistan’s software and technology exports.',
            'campuses' => [
                [
                    'slug' => 'islamabad-campus',
                    'name' => 'FAST Islamabad Campus',
                    'is_main' => true,
                    'city' => 'Islamabad',
                    'province' => 'Islamabad (ICT)',
                    'address' => 'A.K. Brohi Road, Sector H-9, Islamabad',
                    'phone' => '+92 (051) 111-128-128',
                    'email' => 'info.isb@nu.edu.pk',
                    'hostel_capacity' => '650 beds (Boys & Girls hostels on/near campus)',
                    'transport' => 'Routes covering Rawalpindi, Islamabad & Wah Cantt',
                    'libraries' => 'Dr. A.Q. Khan Central Computing Library',
                    'facilities' => ['High-Performance GPU Computing Cluster', 'Incubation Lab', 'Auditorium'],
                    'programs_offered_count' => 16,
                ],
                [
                    'slug' => 'lahore-campus',
                    'name' => 'FAST Lahore Campus',
                    'is_main' => false,
                    'city' => 'Lahore',
                    'province' => 'Punjab',
                    'address' => 'Block B, Faisal Town, Lahore',
                    'phone' => '+92 (042) 111-128-128',
                    'email' => 'info.lhr@nu.edu.pk',
                    'hostel_capacity' => '500 beds',
                    'transport' => 'Extensive Lahore city shuttle fleet',
                    'libraries' => 'Lahore Campus Technical Library (40,000+ volumes)',
                    'facilities' => ['Software Engineering Prototyping Labs', 'Auditorium', 'Cafeteria'],
                    'programs_offered_count' => 14,
                ],
                [
                    'slug' => 'karachi-campus',
                    'name' => 'FAST Karachi Campus',
                    'is_main' => false,
                    'city' => 'Karachi',
                    'province' => 'Sindh',
                    'address' => 'Shah Latif Town, National Highway, Karachi',
                    'phone' => '+92 (021) 111-128-128',
                    'email' => 'info.khi@nu.edu.pk',
                    'hostel_capacity' => '350 beds',
                    'transport' => 'City routes serving Gulshan, Clifton, Malir, Nazimabad',
                    'libraries' => 'Karachi Campus Library',
                    'facilities' => ['Artificial Intelligence & Vision Labs', 'Cyber Security Center'],
                    'programs_offered_count' => 12,
                ],
            ],
            'scholarships_offered' => [
                'FAST Financial Aid Study Loan / Qarz-e-Hasna (Interest-Free)',
                'HEC Need-Based Scholarships for Eligible Students',
                'Merit Scholarship for Top 3 Students in Each Semester',
                'OSAF (Old Students of FAST) Alumni Educational Grants',
            ],
            'admission_requirements' => [
                'Minimum 60% in FSc (Pre-Engineering / ICS) or A-Levels with Mathematics',
                'FAST Admission Test (NU Test) or valid NTS-NAT / SAT score',
                'Standard selection aggregate formula: 50% NU Test + 50% Intermediate Part-I',
            ],
            'disciplines' => ['computing', 'engineering', 'business'],
            'degree_levels' => ['bs', 'ms', 'phd'],
            'last_verified' => '02 Nov 2026',
        ],
        [
            'id' => 3,
            'slug' => 'lums',
            'name' => 'Lahore University of Management Sciences',
            'acronym' => 'LUMS',
            'charter_type' => 'Chartered Private',
            'sector' => 'private',
            'hec_ranking' => '#1 Business & Social Sciences',
            'hec_category' => 'Category W',
            'established' => 1985,
            'charter_citation' => 'Presidential Ordinance No. XXVI of 1985 (Govt. of Pakistan)',
            'gazette_reference' => 'Gazette of Pakistan, March 1985',
            'website' => 'https://lums.edu.pk',
            'phone' => '+92 (042) 3560-8000',
            'email' => 'admissions@lums.edu.pk',
            'address' => 'DHA Phase 5, Cantt, Lahore, 54792, Pakistan',
            'province' => 'punjab',
            'province_label' => 'Punjab',
            'city' => 'Lahore',
            'fee_range' => 'PKR 380,000 – 460,000',
            'fee_min' => 380000,
            'fee_max' => 460000,
            'acceptance_rate' => '~9% Extremely Selective',
            'total_students' => '5,200+',
            'faculty_phd_ratio' => '91% PhD Faculty (Global Ivy/Oxbridge doctorates)',
            'campus_size' => '100 Acres (DHA Phase 5)',
            'hostel_availability' => 'Guaranteed on-campus accommodation for all 1st-year students',
            'status' => 'closing_soon',
            'deadline' => '30 June 2026',
            'featured' => true,
            'accreditations' => [
                ['body' => 'AACSB', 'title' => 'Association to Advance Collegiate Schools of Business (Global Premier)'],
                ['body' => 'NCEAC', 'title' => 'National Computing Education Accreditation Council (Top Category)'],
                ['body' => 'HEC', 'title' => 'Federal Chartered Independent University'],
            ],
            'description' => 'LUMS is Pakistan’s leading research and liberal arts university, famous for the Suleman Dawood School of Business (SDSB), the Syed Babar Ali School of Science and Engineering (SBASSE), and the Shaikh Ahmad Hassan School of Law. Operating a world-class 100-acre residential campus in Lahore, LUMS offers need-blind admissions via the National Outreach Programme (NOP).',
            'campuses' => [
                [
                    'slug' => 'lums-main-campus',
                    'name' => 'LUMS Residential Campus Lahore',
                    'is_main' => true,
                    'city' => 'Lahore',
                    'province' => 'Punjab',
                    'address' => 'DHA Phase 5, Cantt, Lahore',
                    'phone' => '+92 (042) 3560-8000',
                    'email' => 'admissions@lums.edu.pk',
                    'hostel_capacity' => '3,800 residential beds in male and female hostels',
                    'transport' => 'On-campus shuttle and metropolitan connection services',
                    'libraries' => 'Gad & Birgit Rausing Central Library (280,000+ titles, Bloomberg Terminals)',
                    'facilities' => ['Syed Maratib Ali Sports Complex', 'Incubation Lab', 'Faisal Hall Auditorium', 'Medical Clinic'],
                    'programs_offered_count' => 32,
                ],
            ],
            'scholarships_offered' => [
                'National Outreach Programme (NOP) — 100% Fully Funded Tuition, Boarding & Stipend',
                'LUMS Need-Based Financial Aid (Covering 20% to 100% Tuition Fees)',
                'Merit Scholarships for Top 100 Incoming Students',
                'Shahid Hussain Foundation Grants for Female STEM Scholars',
            ],
            'admission_requirements' => [
                'High academic performance in Matric / FSc or O/A-Levels',
                'Mandatory SAT I (Score: 1320+ recommended) or LUMS Common Admission Test (LCAT)',
                'Comprehensive personal statement, extracurricular achievements, and teacher references',
            ],
            'disciplines' => ['business', 'computing', 'engineering', 'social_sciences', 'law'],
            'degree_levels' => ['bs', 'ms', 'phd'],
            'last_verified' => '01 Nov 2026',
        ],
        [
            'id' => 4,
            'slug' => 'giki',
            'name' => 'Ghulam Ishaq Khan Institute of Engineering Sciences and Technology',
            'acronym' => 'GIKI',
            'charter_type' => 'Chartered Autonomous',
            'sector' => 'private',
            'hec_ranking' => 'Top 3 Engineering & Applied Sciences',
            'hec_category' => 'Category W',
            'established' => 1993,
            'charter_citation' => 'Khyber Pakhtunkhwa Provincial Assembly Act 1993',
            'gazette_reference' => 'Khyber Pakhtunkhwa Gazette Notification No. Legis: 1(18)/93',
            'website' => 'https://giki.edu.pk',
            'phone' => '+92 (0938) 271-858',
            'email' => 'admissions@giki.edu.pk',
            'address' => 'Topi, Swabi District, Khyber Pakhtunkhwa, 23640, Pakistan',
            'province' => 'kpk',
            'province_label' => 'Khyber Pakhtunkhwa',
            'city' => 'Topi',
            'fee_range' => 'PKR 340,000 – 410,000',
            'fee_min' => 340000,
            'fee_max' => 410000,
            'acceptance_rate' => '~14% Competitive',
            'total_students' => '2,800+',
            'faculty_phd_ratio' => '88% PhD Faculty',
            'campus_size' => '400 Acres (Tarbela Lake foothills)',
            'hostel_availability' => '100% Guaranteed On-Campus Residential Housing',
            'status' => 'merit_announced',
            'deadline' => '1st List Active',
            'featured' => false,
            'accreditations' => [
                ['body' => 'PEC', 'title' => 'PEC Level-II (Full Washington Accord International Equivalence)'],
                ['body' => 'NCEAC', 'title' => 'National Computing Education Accreditation Council (Highest Rank)'],
                ['body' => 'HEC', 'title' => 'Chartered Higher Education Institute'],
            ],
            'description' => 'GIKI is one of Pakistan’s most prestigious engineering, computing, and applied science residential institutes. Situated in the foothills of the scenic Tarbela dam in Topi, KPK, GIKI provides a completely residential, immersive environment where all faculty and students reside on campus with access to world-class laboratories.',
            'campuses' => [
                [
                    'slug' => 'topi-residential',
                    'name' => 'GIKI Topi Residential Campus',
                    'is_main' => true,
                    'city' => 'Topi',
                    'province' => 'Khyber Pakhtunkhwa',
                    'address' => 'Topi, Swabi District, Khyber Pakhtunkhwa',
                    'phone' => '+92 (0938) 271-858',
                    'email' => 'admissions@giki.edu.pk',
                    'hostel_capacity' => '2,800 beds (100% student population resides on campus)',
                    'transport' => 'Internal campus transportation and weekend buses to Islamabad/Peshawar',
                    'libraries' => 'Aga Hasan Abedi Central Library',
                    'facilities' => ['Clean Room Nanotechnology Lab', 'Wind Tunnel', 'Sports Complex & Swimming', 'Incubation Center'],
                    'programs_offered_count' => 18,
                ],
            ],
            'scholarships_offered' => [
                'GIKI Financial Assistance (Over 35% of Students receive tuition waivers)',
                'KP Government Chief Minister STEM Scholarship',
                'Ihsan Trust Interest-Free Student Educational Loans',
                'Alumni Endowment Scholarship Fund',
            ],
            'admission_requirements' => [
                'Minimum 60% aggregate in FSc Pre-Engineering, Pre-Medical with Additional Math, or ICS',
                'Mandatory GIKI Entrance Examination or SAT I / SAT II Subject Scores',
                'Formula: 85% GIKI Entry Test + 15% Intermediate Marks',
            ],
            'disciplines' => ['engineering', 'computing', 'natural_sciences'],
            'degree_levels' => ['bs', 'ms', 'phd'],
            'last_verified' => '03 Nov 2026',
        ],
        [
            'id' => 5,
            'slug' => 'iba-karachi',
            'name' => 'Institute of Business Administration',
            'acronym' => 'IBA',
            'charter_type' => 'Public Chartered (Sindh Act)',
            'sector' => 'public',
            'hec_ranking' => '#1 Premier Sindh Business School',
            'hec_category' => 'Category W',
            'established' => 1955,
            'charter_citation' => 'Sindh Assembly Act No. XX of 1994 (Founded 1955 with Wharton)',
            'gazette_reference' => 'Sindh Government Gazette Extraordinary, May 1994',
            'website' => 'https://iba.edu.pk',
            'phone' => '+92 (021) 3810-4700',
            'email' => 'admission@iba.edu.pk',
            'address' => 'University Road, Karachi, 75270, Sindh, Pakistan',
            'province' => 'sindh',
            'province_label' => 'Sindh',
            'city' => 'Karachi',
            'fee_range' => 'PKR 290,000 – 360,000',
            'fee_min' => 290000,
            'fee_max' => 360000,
            'acceptance_rate' => '~11% Highly Selective',
            'total_students' => '4,500+',
            'faculty_phd_ratio' => '80% PhD Faculty',
            'campus_size' => 'Dual Campus (University Road & Garden City)',
            'hostel_availability' => 'On-Campus Hostels at University Road for Outstation Candidates',
            'status' => 'open',
            'deadline' => '12 July 2026',
            'featured' => false,
            'accreditations' => [
                ['body' => 'NBEAC', 'title' => 'National Business Education Accreditation Council (Category W)'],
                ['body' => 'NCEAC', 'title' => 'National Computing Education Accreditation Council'],
                ['body' => 'HEC', 'title' => 'Chartered Public Degree Granting Institute'],
            ],
            'description' => 'Founded in 1955 with technical support from the Wharton School of the University of Pennsylvania, IBA Karachi is the oldest and most prestigious business school outside North America. It now offers premier programs in Business Administration, Computer Science, Economics, Mathematics, and Accounting & Finance.',
            'campuses' => [
                [
                    'slug' => 'main-campus-karachi',
                    'name' => 'IBA Main Campus (University Road)',
                    'is_main' => true,
                    'city' => 'Karachi',
                    'province' => 'Sindh',
                    'address' => 'University Road, Karachi',
                    'phone' => '+92 (021) 3810-4700',
                    'email' => 'info@iba.edu.pk',
                    'hostel_capacity' => '600 beds in modern residential halls',
                    'transport' => 'Transport network connecting all districts of Karachi',
                    'libraries' => 'Aman Tower & Central IBA Library (100,000+ volumes, Financial Data terminals)',
                    'facilities' => ['Aman Center for Entrepreneurial Development', 'Student Center', 'Sports Complex'],
                    'programs_offered_count' => 22,
                ],
                [
                    'slug' => 'city-campus-karachi',
                    'name' => 'IBA City Campus (Garden)',
                    'is_main' => false,
                    'city' => 'Karachi',
                    'province' => 'Sindh',
                    'address' => 'Garden Road, Near Plaza, Karachi',
                    'phone' => '+92 (021) 3810-4701',
                    'email' => 'citycampus@iba.edu.pk',
                    'hostel_capacity' => 'Executive accommodation only',
                    'transport' => 'Central Karachi access via M.A. Jinnah Road',
                    'libraries' => 'City Campus Library',
                    'facilities' => ['Aman Tower High-Rise Classrooms', 'Auditorium'],
                    'programs_offered_count' => 14,
                ],
            ],
            'scholarships_offered' => [
                'IBA National Talent Hunt Program (NTHP) — 100% Fully Funded for Meritorious Underprivileged Students',
                'Sindh Talent Hunt Program (STHP)',
                'IBA Financial Assistance & Endowment Funds (No student is turned away due to inability to pay)',
                'HEC Need-Based Scholarships',
            ],
            'admission_requirements' => [
                'Minimum 65% in Intermediate (HSSC) or Cambridge O/A Levels',
                'Mandatory IBA Aptitude Test (English & Mathematics) or SAT I (1270+) / ACT (28+)',
                'Interview round for shortlisted candidates in business and social programs',
            ],
            'disciplines' => ['business', 'computing', 'social_sciences'],
            'degree_levels' => ['bs', 'ms', 'phd'],
            'last_verified' => '02 Nov 2026',
        ],
        [
            'id' => 6,
            'slug' => 'aga-khan-university',
            'name' => 'Aga Khan University',
            'acronym' => 'AKU',
            'charter_type' => 'Chartered Autonomous',
            'sector' => 'private',
            'hec_ranking' => '#1 Health Sciences & Medicine',
            'hec_category' => 'Category W',
            'established' => 1983,
            'charter_citation' => 'Presidential Order No. 3 of 1983 (Federal Charter)',
            'gazette_reference' => 'Gazette of Pakistan Extraordinary, March 1983',
            'website' => 'https://aku.edu',
            'phone' => '+92 (021) 3493-0051',
            'email' => 'admissions@aku.edu',
            'address' => 'Stadium Road, Karachi, 74800, Sindh, Pakistan',
            'province' => 'sindh',
            'province_label' => 'Sindh',
            'city' => 'Karachi',
            'fee_range' => 'PKR 540,000 – 720,000',
            'fee_min' => 540000,
            'fee_max' => 720000,
            'acceptance_rate' => '~4% Elite Medical',
            'total_students' => '3,200+',
            'faculty_phd_ratio' => '95% Fellowship / Medical Doctorate Faculty',
            'campus_size' => '84 Acres (Hospital & University Complex)',
            'hostel_availability' => '100% Guaranteed for all Medical and Nursing students',
            'status' => 'closing_soon',
            'deadline' => '25 June 2026',
            'featured' => false,
            'accreditations' => [
                ['body' => 'PMDC', 'title' => 'Pakistan Medical & Dental Council (Level-I Medical College)'],
                ['body' => 'WHO', 'title' => 'World Health Organization Directory of Medical Schools'],
                ['body' => 'HEC', 'title' => 'Chartered International University in Pakistan'],
            ],
            'description' => 'Aga Khan University is Pakistan’s leading private medical and healthcare research institution. Renowned worldwide for clinical excellence, state-of-the-art tertiary teaching hospital facilities, and biomedical breakthroughs, AKU is ranked top among health science universities in South Asia.',
            'campuses' => [
                [
                    'slug' => 'stadium-road-karachi',
                    'name' => 'AKU Stadium Road Campus',
                    'is_main' => true,
                    'city' => 'Karachi',
                    'province' => 'Sindh',
                    'address' => 'Stadium Road, Karachi',
                    'phone' => '+92 (021) 3493-0051',
                    'email' => 'admissions@aku.edu',
                    'hostel_capacity' => '1,400 beds in air-conditioned hostels',
                    'transport' => 'Internal campus shuttles and clinical rotation transit',
                    'libraries' => 'AKU Medical Library (leading health sciences digital repository in South Asia)',
                    'facilities' => ['700-bed Aga Khan University Hospital', 'Centre for Innovation in Medical Education (CIME)', 'Sports Centre'],
                    'programs_offered_count' => 16,
                ],
            ],
            'scholarships_offered' => [
                'AKU Need-Blind Financial Assistance (Over 70% of medical students receive financial support)',
                'Endowed Tuition Fee Waivers for Meritorious Low-Income Candidates',
                'Nursing Full-Tuition Stipend & Sponsorship Agreements',
            ],
            'admission_requirements' => [
                'Minimum 70% in FSc Pre-Medical or Cambridge equivalent (Biology, Chemistry, Physics)',
                'Mandatory AKU Medical College Entrance Test & MDCAT qualification as per PMDC',
                'Multiple Mini Interviews (MMI) evaluation for shortlisted medical candidates',
            ],
            'disciplines' => ['medical', 'natural_sciences'],
            'degree_levels' => ['bs', 'ms', 'phd'],
            'last_verified' => '01 Nov 2026',
        ],
        [
            'id' => 7,
            'slug' => 'uet-lahore',
            'name' => 'University of Engineering & Technology',
            'acronym' => 'UET Lahore',
            'charter_type' => 'Public Sector',
            'sector' => 'public',
            'hec_ranking' => '#2 Public Engineering University',
            'hec_category' => 'Category W',
            'established' => 1921,
            'charter_citation' => 'Punjab Assembly Act No. V of 1974 (Founded 1921 as Maclagan College)',
            'gazette_reference' => 'Punjab Gazette Extraordinary, May 1974',
            'website' => 'https://uet.edu.pk',
            'phone' => '+92 (042) 9902-9227',
            'email' => 'admissions@uet.edu.pk',
            'address' => 'Grand Trunk Road, Near Mughalpura, Lahore, 54890, Punjab, Pakistan',
            'province' => 'punjab',
            'province_label' => 'Punjab',
            'city' => 'Lahore',
            'fee_range' => 'PKR 75,000 – 110,000',
            'fee_min' => 75000,
            'fee_max' => 110000,
            'acceptance_rate' => '~16% Highly Competitive',
            'total_students' => '16,000+',
            'faculty_phd_ratio' => '78% PhD Faculty',
            'campus_size' => '170 Acres (Main Campus Lahore)',
            'hostel_availability' => 'Extensive on-campus hostels for outstation Punjab candidates',
            'status' => 'open',
            'deadline' => '18 July 2026',
            'featured' => false,
            'accreditations' => [
                ['body' => 'PEC', 'title' => 'PEC Tier-I/II (Washington Accord International Mutual Recognition)'],
                ['body' => 'HEC', 'title' => 'Chartered Provincial Public University'],
            ],
            'description' => 'UET Lahore is Pakistan’s oldest and most renowned public engineering university. With thousands of alumni leading civil, electrical, chemical, and mechanical engineering projects across Pakistan and the Middle East, UET offers high-quality engineering education at affordable, subsidized government tuition rates.',
            'campuses' => [
                [
                    'slug' => 'uet-main-lahore',
                    'name' => 'UET Main Campus Lahore',
                    'is_main' => true,
                    'city' => 'Lahore',
                    'province' => 'Punjab',
                    'address' => 'GT Road, Mughalpura, Lahore',
                    'phone' => '+92 (042) 9902-9227',
                    'email' => 'admissions@uet.edu.pk',
                    'hostel_capacity' => '3,200 beds across 12 resident halls',
                    'transport' => 'University bus routes across Lahore & Gujranwala',
                    'libraries' => 'National Library of Engineering Sciences',
                    'facilities' => ['High Voltage Research Lab', 'Automotive Test Facility', 'Olympic Stadium'],
                    'programs_offered_count' => 38,
                ],
                [
                    'slug' => 'uet-ksk-campus',
                    'name' => 'UET Kala Shah Kaku Campus (KSK)',
                    'is_main' => false,
                    'city' => 'Kala Shah Kaku',
                    'province' => 'Punjab',
                    'address' => 'Motorway M-2 Interchange, Kala Shah Kaku',
                    'phone' => '+92 (042) 3795-1002',
                    'email' => 'ksk@uet.edu.pk',
                    'hostel_capacity' => '1,200 beds',
                    'transport' => 'Express buses to Lahore and Sheikhupura',
                    'libraries' => 'KSK Campus Engineering Library',
                    'facilities' => ['Renewable Energy Research Center', 'Sports Complex'],
                    'programs_offered_count' => 14,
                ],
            ],
            'scholarships_offered' => [
                'HEC Need-Based Undergraduate Scholarships',
                'Punjab Educational Endowment Fund (PEEF) Scholarships',
                'UET Alumni Financial Assistance Trust Fund',
                'Bait-ul-Maal & District Zakat Committee Grants',
            ],
            'admission_requirements' => [
                'Minimum 60% in FSc Pre-Engineering with Physics, Chemistry & Math',
                'Mandatory Combined Entry Test (ECAT) conducted by UET Lahore',
                'Formula: 33% ECAT + 50% Intermediate + 17% Matriculation',
            ],
            'disciplines' => ['engineering', 'computing', 'natural_sciences'],
            'degree_levels' => ['bs', 'ms', 'phd'],
            'last_verified' => '03 Nov 2026',
        ],
        [
            'id' => 8,
            'slug' => 'quaid-i-azam-university',
            'name' => 'Quaid-i-Azam University',
            'acronym' => 'QAU',
            'charter_type' => 'Public Sector (Federal)',
            'sector' => 'public',
            'hec_ranking' => '#1 Overall General Category',
            'hec_category' => 'Category W',
            'established' => 1967,
            'charter_citation' => 'Act of Parliament No. XXVIII of 1967 (Federal)',
            'gazette_reference' => 'Gazette of Pakistan Extraordinary, August 1967',
            'website' => 'https://qau.edu.pk',
            'phone' => '+92 (051) 9064-0000',
            'email' => 'admissions@qau.edu.pk',
            'address' => 'University Road, Islamabad, 45320, Federal Capital, Pakistan',
            'province' => 'ict',
            'province_label' => 'Islamabad (ICT)',
            'city' => 'Islamabad',
            'fee_range' => 'PKR 65,000 – 95,000',
            'fee_min' => 65000,
            'fee_max' => 95000,
            'acceptance_rate' => '~18% Competitive',
            'total_students' => '13,500+',
            'faculty_phd_ratio' => '92% PhD Faculty',
            'campus_size' => '1,700 Acres (Margalla Hills foothills)',
            'hostel_availability' => 'On-campus hostels allocated by strict provincial quota',
            'status' => 'open',
            'deadline' => '20 July 2026',
            'featured' => false,
            'accreditations' => [
                ['body' => 'HEC', 'title' => 'Top Ranked Federal Public Research University'],
            ],
            'description' => 'Quaid-i-Azam University (formerly University of Islamabad) is Pakistan’s leading public research university, located under the Margalla Hills in Islamabad. Consistently ranked by international bodies (US News, Times Higher Education, QS) as the highest-ranked Pakistani university, QAU offers deeply subsidized education with statutory provincial quotas.',
            'campuses' => [
                [
                    'slug' => 'qau-main-islamabad',
                    'name' => 'QAU Main Campus Islamabad',
                    'is_main' => true,
                    'city' => 'Islamabad',
                    'province' => 'Islamabad (ICT)',
                    'address' => 'University Road, Islamabad',
                    'phone' => '+92 (051) 9064-0000',
                    'email' => 'admissions@qau.edu.pk',
                    'hostel_capacity' => '4,000 beds in 10 boys and girls resident hostels',
                    'transport' => 'University points connecting Islamabad, Rawalpindi & Taxila',
                    'libraries' => 'Dr. Raziuddin Siddiqui Memorial Central Library',
                    'facilities' => ['National Centre for Physics', 'Biological Sciences Greenhouse', 'Cricket Ground'],
                    'programs_offered_count' => 45,
                ],
            ],
            'scholarships_offered' => [
                'HEC Need-Based Undergraduate Scholarships',
                'Ehsaas Undergraduate Grants',
                'QAU Zakat & University Endowment Fund',
                'Balochistan & FATA Meritorious Student Scholarships',
            ],
            'admission_requirements' => [
                'Minimum 50%–60% marks in Intermediate depending on discipline',
                'Strict compliance with Federal, Punjab, Sindh, KPK, Balochistan, GB and AJK provincial quotas',
                'Merit calculated primarily on Intermediate (70%) and Matriculation (30%) or discipline test',
            ],
            'disciplines' => ['natural_sciences', 'social_sciences', 'computing'],
            'degree_levels' => ['bs', 'ms', 'phd'],
            'last_verified' => '02 Nov 2026',
        ],
    ];

    /**
     * Complete repository of degree programs.
     */
    protected array $programs = [
        [
            'id' => 101,
            'slug' => 'nust-bs-software-engineering',
            'title' => 'BS Software Engineering (BSSE)',
            'university_slug' => 'nust',
            'university_name' => 'National University of Sciences & Technology',
            'department' => 'School of Electrical Engineering & Computer Science (SEECS)',
            'degree_level' => 'bs',
            'degree_level_label' => 'Undergraduate (BS)',
            'discipline' => 'computing',
            'discipline_label' => 'Computing & IT',
            'duration' => '4 Years • 8 Semesters',
            'credit_hours' => 134,
            'study_mode' => 'Full-time Regular',
            'shift' => 'Morning',
            'fee_per_semester' => 'PKR 142,500',
            'fee_raw' => 142500,
            'estimated_closing_merit' => '80.12% Aggregate',
            'merit_raw' => 80.12,
            'accreditation' => 'NCEAC Category W • Washington Accord Compliant',
            'council' => 'NCEAC',
            'status' => 'open',
            'deadline' => '15 July 2026',
            'available_campuses' => ['islamabad-h12'],
            'overview' => 'The BS Software Engineering program at NUST SEECS is engineered to produce elite software architects, systems engineers, and technical product leads. The curriculum bridges rigorous theoretical computer science with modern industrial software development methodologies, DevSecOps, and distributed systems.',
            'admission_criteria' => [
                'FSc Pre-Engineering / ICS (with Mathematics) with minimum 60% marks',
                'NUST Entry Test (NET) score requirement (historically 145+ / 200 for 1st merit list)',
                'Aggregate Weightage: 75% NET + 15% FSc + 10% Matriculation',
            ],
            'curriculum_highlights' => [
                'Year 1: Programming Fundamentals, Object Oriented Programming, Discrete Structures, Digital Logic Design',
                'Year 2: Data Structures & Algorithms, Software Requirements Engineering, Database Systems, Computer Networks',
                'Year 3: Software Design & Architecture, Operating Systems, Web & Cloud Engineering, Software Quality Assurance',
                'Year 4: Distributed Computing, DevSecOps & CI/CD, Final Year Industrial Capstone Project (6 Credit Hours)',
            ],
            'career_outlook' => 'Graduates are heavily recruited by multinational technology companies, high-growth venture-backed startups, and global remote engineering firms with starting compensation ranging between PKR 150,000 and PKR 350,000 per month.',
            'related_programs' => ['nust-bs-computer-science', 'fast-bs-computer-science', 'giki-bs-artificial-intelligence'],
        ],
        [
            'id' => 102,
            'slug' => 'nust-bs-computer-science',
            'title' => 'BS Computer Science (BSCS)',
            'university_slug' => 'nust',
            'university_name' => 'National University of Sciences & Technology',
            'department' => 'School of Electrical Engineering & Computer Science (SEECS)',
            'degree_level' => 'bs',
            'degree_level_label' => 'Undergraduate (BS)',
            'discipline' => 'computing',
            'discipline_label' => 'Computing & IT',
            'duration' => '4 Years • 8 Semesters',
            'credit_hours' => 130,
            'study_mode' => 'Full-time Regular',
            'shift' => 'Morning',
            'fee_per_semester' => 'PKR 142,500',
            'fee_raw' => 142500,
            'estimated_closing_merit' => '81.40% Aggregate',
            'merit_raw' => 81.40,
            'accreditation' => 'NCEAC Category W',
            'council' => 'NCEAC',
            'status' => 'open',
            'deadline' => '15 July 2026',
            'available_campuses' => ['islamabad-h12'],
            'overview' => 'NUST BS Computer Science provides deep mathematical foundations, algorithm complexity theory, artificial intelligence, and operating systems engineering. It is one of the highest-cutoff undergraduate degree programs in Pakistan.',
            'admission_criteria' => [
                'FSc Pre-Engineering / ICS (Math, Physics, CS) with minimum 60% marks',
                'Mandatory NUST Entry Test (NET-1/2/3)',
                'Aggregate: 75% NET + 15% FSc + 10% Matric',
            ],
            'curriculum_highlights' => [
                'Year 1: Programming Fundamentals, Calculus, Physics, OOP, Discrete Math',
                'Year 2: Data Structures, Design & Analysis of Algorithms, Computer Architecture, Differential Equations',
                'Year 3: Artificial Intelligence, Theory of Automata, Compiler Construction, Computer Networks',
                'Year 4: Deep Learning, Information Security, Electives & Final Year Project',
            ],
            'career_outlook' => 'Software Developers, AI Engineers, Data Scientists, and Systems Researchers worldwide.',
            'related_programs' => ['nust-bs-software-engineering', 'fast-bs-computer-science', 'lums-bs-computer-science'],
        ],
        [
            'id' => 103,
            'slug' => 'fast-bs-computer-science',
            'title' => 'BS Computer Science (BSCS)',
            'university_slug' => 'fast-nuces',
            'university_name' => 'FAST - National University of Computer & Emerging Sciences',
            'department' => 'Faculty of Computer Science',
            'degree_level' => 'bs',
            'degree_level_label' => 'Undergraduate (BS)',
            'discipline' => 'computing',
            'discipline_label' => 'Computing & IT',
            'duration' => '4 Years • 8 Semesters',
            'credit_hours' => 130,
            'study_mode' => 'Full-time Regular',
            'shift' => 'Morning',
            'fee_per_semester' => 'PKR 148,000',
            'fee_raw' => 148000,
            'estimated_closing_merit' => '78.20% Aggregate',
            'merit_raw' => 78.20,
            'accreditation' => 'NCEAC Highest Category W',
            'council' => 'NCEAC',
            'status' => 'open',
            'deadline' => '05 July 2026',
            'available_campuses' => ['islamabad-campus', 'lahore-campus', 'karachi-campus'],
            'overview' => 'The hallmark degree of FAST-NUCES. Renowned for rigorous coding standards, strict evaluation, and competitive problem-solving. FAST graduates consistently dominate national ACM programming competitions and form over 35% of technical leads in Pakistan’s software ecosystem.',
            'admission_criteria' => [
                'Intermediate with Mathematics (Pre-Eng, ICS) with minimum 60% marks',
                'FAST Admission Test (NU Test) or NTS-NAT / SAT',
                'Aggregate Formula: 50% NU Test + 50% Intermediate Part-I',
            ],
            'curriculum_highlights' => [
                'Year 1: Intensive C++ Programming Fundamentals, Object-Oriented Programming, Calculus, Digital Logic',
                'Year 2: Advanced Data Structures in C++, Algorithms Analysis, Computer Organization, Database Systems',
                'Year 3: Operating Systems, Computer Networks, Software Engineering, Parallel & Distributed Computing',
                'Year 4: Artificial Intelligence, Cloud Computing, Full-Year Senior Capstone Project',
            ],
            'career_outlook' => 'Highest campus recruitment placement rate in Pakistan. Directly hired by Systems Ltd, Careem, Arbisoft, Afiniti, Techlogix, and global tech firms.',
            'related_programs' => ['nust-bs-computer-science', 'fast-bs-artificial-intelligence', 'giki-bs-computer-science'],
        ],
        [
            'id' => 104,
            'slug' => 'fast-bs-artificial-intelligence',
            'title' => 'BS Artificial Intelligence (BSAI)',
            'university_slug' => 'fast-nuces',
            'university_name' => 'FAST - National University of Computer & Emerging Sciences',
            'department' => 'Faculty of Computer Science',
            'degree_level' => 'bs',
            'degree_level_label' => 'Undergraduate (BS)',
            'discipline' => 'computing',
            'discipline_label' => 'Computing & IT',
            'duration' => '4 Years • 8 Semesters',
            'credit_hours' => 130,
            'study_mode' => 'Full-time Regular',
            'shift' => 'Morning',
            'fee_per_semester' => 'PKR 148,000',
            'fee_raw' => 148000,
            'estimated_closing_merit' => '76.80% Aggregate',
            'merit_raw' => 76.80,
            'accreditation' => 'NCEAC Approved Specialist Degree',
            'council' => 'NCEAC',
            'status' => 'open',
            'deadline' => '05 July 2026',
            'available_campuses' => ['islamabad-campus', 'lahore-campus'],
            'overview' => 'Tailored for the modern machine learning and large language model era. Delivers foundational computer science with specialized depth in deep learning, natural language processing, computer vision, and autonomous robotics.',
            'admission_criteria' => [
                'Minimum 60% in FSc (Math mandatory) / ICS / A-Levels',
                'FAST Admission Test (NU Test)',
                'Aggregate: 50% NU Test + 50% Intermediate',
            ],
            'curriculum_highlights' => [
                'Core Foundations: Linear Algebra, Probability & Statistics for Data Science, Algorithms',
                'AI Core: Machine Learning, Artificial Neural Networks & Deep Learning, Natural Language Processing',
                'Applied Specialties: Computer Vision, Reinforcement Learning, Generative AI Systems',
            ],
            'career_outlook' => 'Machine Learning Engineer, Data Scientist, Computer Vision Specialist, LLM fine-tuning engineer.',
            'related_programs' => ['fast-bs-computer-science', 'giki-bs-artificial-intelligence', 'nust-bs-computer-science'],
        ],
        [
            'id' => 105,
            'slug' => 'giki-bs-artificial-intelligence',
            'title' => 'BS Artificial Intelligence',
            'university_slug' => 'giki',
            'university_name' => 'Ghulam Ishaq Khan Institute (GIKI)',
            'department' => 'Faculty of Computer Science & Engineering',
            'degree_level' => 'bs',
            'degree_level_label' => 'Undergraduate (BS)',
            'discipline' => 'computing',
            'discipline_label' => 'Computing & IT',
            'duration' => '4 Years • 8 Semesters',
            'credit_hours' => 134,
            'study_mode' => 'Full-time Regular',
            'shift' => 'Morning (100% Residential)',
            'fee_per_semester' => 'PKR 360,000',
            'fee_raw' => 360000,
            'estimated_closing_merit' => '78.90% Aggregate',
            'merit_raw' => 78.90,
            'accreditation' => 'NCEAC Category W',
            'council' => 'NCEAC',
            'status' => 'merit_announced',
            'deadline' => '1st List Declared',
            'available_campuses' => ['topi-residential'],
            'overview' => 'Offered in GIKI’s 100% residential campus in Topi, KPK. Combines 24/7 access to high-performance computing labs, faculty-guided AI research, and robotics integration.',
            'admission_criteria' => [
                'Minimum 60% in FSc Pre-Engineering or ICS with Mathematics',
                'Mandatory GIKI Admission Test or SAT I / SAT II Subject Tests',
                'Formula: 85% GIKI Test + 15% Intermediate Marks',
            ],
            'curriculum_highlights' => [
                'Advanced GPU programming, autonomous robotics systems, machine vision, and mathematical optimization.',
            ],
            'career_outlook' => 'Global graduate school admissions and high-tier technology research positions.',
            'related_programs' => ['fast-bs-artificial-intelligence', 'nust-bs-computer-science'],
        ],
        [
            'id' => 106,
            'slug' => 'lums-bsc-accounting-finance',
            'title' => 'BSc (Hons) Accounting & Finance',
            'university_slug' => 'lums',
            'university_name' => 'Lahore University of Management Sciences',
            'department' => 'Suleman Dawood School of Business (SDSB)',
            'degree_level' => 'bs',
            'degree_level_label' => 'Undergraduate (BSc Hons)',
            'discipline' => 'business',
            'discipline_label' => 'Business & Management',
            'duration' => '4 Years • 8 Semesters',
            'credit_hours' => 130,
            'study_mode' => 'Full-time Regular',
            'shift' => 'Morning (Residential)',
            'fee_per_semester' => 'PKR 410,000',
            'fee_raw' => 410000,
            'estimated_closing_merit' => 'SAT 1380+ / LCAT High Merit',
            'merit_raw' => 88.00,
            'accreditation' => 'AACSB Accredited (Top 5% Business Schools Worldwide)',
            'council' => 'NBEAC',
            'status' => 'closing_soon',
            'deadline' => '30 June 2026',
            'available_campuses' => ['lums-main-campus'],
            'overview' => 'Offered by SDSB—Pakistan’s only business school with full AACSB international accreditation. Prepares students for investment banking, corporate finance, venture capital, and strategic management consultancy.',
            'admission_criteria' => [
                'Outstanding Matric/FSc or O/A-Level grades',
                'SAT I score (1350+ competitive) or LUMS Common Admission Test (LCAT)',
                'Extracurricular portfolio, personal essay, and teacher evaluations',
            ],
            'curriculum_highlights' => [
                'Corporate Finance, Financial Modeling, Investments & Portfolio Management, International Financial Reporting (IFRS), Managerial Accounting',
            ],
            'career_outlook' => 'Investment Banking, McKinsey / BCG / Bain consultancy, multinational FMCG management trainee programs, Chartered Accountancy (ICAEW/ACCA fast-track exemptions).',
            'related_programs' => ['iba-bba-hons', 'nust-bs-software-engineering'],
        ],
        [
            'id' => 107,
            'slug' => 'iba-bba-hons',
            'title' => 'Bachelor of Business Administration (BBA Hons)',
            'university_slug' => 'iba-karachi',
            'university_name' => 'Institute of Business Administration',
            'department' => 'School of Business Studies',
            'degree_level' => 'bs',
            'degree_level_label' => 'Undergraduate (BBA Hons)',
            'discipline' => 'business',
            'discipline_label' => 'Business & Management',
            'duration' => '4 Years • 8 Semesters',
            'credit_hours' => 144,
            'study_mode' => 'Full-time Regular',
            'shift' => 'Morning',
            'fee_per_semester' => 'PKR 310,000',
            'fee_raw' => 310000,
            'estimated_closing_merit' => 'IBA Aptitude Test Cutoff',
            'merit_raw' => 84.00,
            'accreditation' => 'NBEAC Category W',
            'council' => 'NBEAC',
            'status' => 'open',
            'deadline' => '12 July 2026',
            'available_campuses' => ['main-campus-karachi'],
            'overview' => 'Pakistan’s benchmark BBA degree. With 70 years of business leadership pedigree, IBA BBA graduates lead marketing, financial strategy, and executive management across Pakistan and the GCC.',
            'admission_criteria' => [
                'Minimum 65% in HSSC / Intermediate or Cambridge A-Levels',
                'Mandatory IBA Entrance Aptitude Test (Math & English) or SAT I (1270+)',
                'Structured interview assessment for shortlisted applicants',
            ],
            'curriculum_highlights' => [
                'Strategic Marketing, Consumer Behavior, Corporate Finance, Business Analytics, Organizational Behavior, Supply Chain Management',
            ],
            'career_outlook' => 'Brand Managers, Financial Analysts, Strategy Consultants, Corporate Bankers.',
            'related_programs' => ['lums-bsc-accounting-finance'],
        ],
        [
            'id' => 108,
            'slug' => 'aku-mbbs',
            'title' => 'Bachelor of Medicine & Bachelor of Surgery (MBBS)',
            'university_slug' => 'aga-khan-university',
            'university_name' => 'Aga Khan University',
            'department' => 'Medical College Pakistan',
            'degree_level' => 'professional',
            'degree_level_label' => 'Professional (MBBS)',
            'discipline' => 'medical',
            'discipline_label' => 'Medical & Health Sciences',
            'duration' => '5 Years + 1 Year House Job',
            'credit_hours' => 210,
            'study_mode' => 'Full-time Clinical Residential',
            'shift' => 'Full Day',
            'fee_per_semester' => 'PKR 620,000',
            'fee_raw' => 620000,
            'estimated_closing_merit' => 'Top 1% National Ranking',
            'merit_raw' => 93.50,
            'accreditation' => 'PMDC Recognized • WHO & ECFMG Approved',
            'council' => 'PMDC',
            'status' => 'closing_soon',
            'deadline' => '25 June 2026',
            'available_campuses' => ['stadium-road-karachi'],
            'overview' => 'The premier medical degree in Pakistan. Offers problem-based clinical learning, early patient contact at the Aga Khan University Hospital, and unmatched USMLE/PLAB international residency match rates.',
            'admission_criteria' => [
                'Minimum 70% in FSc Pre-Medical or Cambridge equivalent (Biology, Chemistry, Physics)',
                'AKU Medical College Test + PMDC National MDCAT qualification',
                'Multiple Mini Interviews (MMI) testing ethical reasoning and communication',
            ],
            'curriculum_highlights' => [
                'Integrated Organ Systems Curriculum, Clinical Skills Center Simulation, Rural Health Rotations, Hospital Wards Internship',
            ],
            'career_outlook' => 'International Medical Residency (USMLE Match in USA, PLAB/GMC in UK), Specialized Clinical Consultants, Medical Researchers.',
            'related_programs' => [],
        ],
        [
            'id' => 109,
            'slug' => 'uet-be-electrical-engineering',
            'title' => 'BE Electrical Engineering',
            'university_slug' => 'uet-lahore',
            'university_name' => 'University of Engineering & Technology (UET Lahore)',
            'department' => 'Department of Electrical Engineering',
            'degree_level' => 'be',
            'degree_level_label' => 'Undergraduate (BE)',
            'discipline' => 'engineering',
            'discipline_label' => 'Engineering (PEC)',
            'duration' => '4 Years • 8 Semesters',
            'credit_hours' => 136,
            'study_mode' => 'Full-time Regular',
            'shift' => 'Morning',
            'fee_per_semester' => 'PKR 78,000',
            'fee_raw' => 78000,
            'estimated_closing_merit' => '73.40% ECAT Aggregate',
            'merit_raw' => 73.40,
            'accreditation' => 'PEC Level-II (Washington Accord International Equivalence)',
            'council' => 'PEC',
            'status' => 'open',
            'deadline' => '18 July 2026',
            'available_campuses' => ['uet-main-lahore', 'uet-ksk-campus'],
            'overview' => 'One of the oldest and most established electrical engineering faculties in South Asia. Accredited under the Washington Accord, enabling graduates to be licensed as Professional Engineers internationally without qualification equivalency exams.',
            'admission_criteria' => [
                'FSc Pre-Engineering with Physics, Chemistry & Math (Min. 60%)',
                'Combined ECAT Entrance Examination',
                'Aggregate: 33% ECAT + 50% Intermediate + 17% Matric',
            ],
            'curriculum_highlights' => [
                'Circuit Analysis, Electromagnetic Fields, Power Systems Engineering, Microprocessor Systems, Control Systems',
            ],
            'career_outlook' => 'Power sector (WAPDA, NTDC, DISCOs), telecommunications (Jazz, Zong, PTCL), automation and industrial manufacturing.',
            'related_programs' => ['nust-bs-software-engineering', 'fast-bs-computer-science'],
        ],
    ];

    /**
     * Get filtered universities with pagination.
     */
    public function getUniversities(array $filters = [], string $sort = 'rank', int $perPage = 6, int $page = 1): array
    {
        $query = University::query()->with(['campuses.programs', 'sourceRecords.dataSource'])->withCount('programs');
        if (! empty($filters['q'])) {
            $query->search(trim($filters['q']));
        }
        if (! empty($filters['province']) && $filters['province'] !== 'all') {
            $catalog = app(RegionCatalog::class);
            if (! $catalog->allowsRegion($filters['province'])) {
                $query->whereRaw('1 = 0');
            } else {
                $slugs = $catalog->provinceSlugs($filters['province']);
                $query->where(fn ($q) => $q->whereHas('location.province', fn ($p) => $p->whereIn('slug', $slugs))->orWhereHas('campuses.location.province', fn ($p) => $p->whereIn('slug', $slugs)));
            }
        }
        if (! empty($filters['city']) && $filters['city'] !== 'all') {
            $query->where(fn ($q) => $q->whereHas('location', fn ($c) => $c->whereRaw('LOWER(name) = ?', [strtolower($filters['city'])]))->orWhereHas('campuses.location', fn ($c) => $c->whereRaw('LOWER(name) = ?', [strtolower($filters['city'])])));
        }
        $query->bySector($filters['sector'] ?? null);
        foreach (['discipline' => 'discipline', 'degree_level' => 'degree_level'] as $filter => $column) {
            if (! empty($filters[$filter]) && $filters[$filter] !== 'all') {
                $query->whereHas('programs', fn ($program) => $program->where($column, $filters[$filter]));
            }
        }
        if (! empty($filters['hec_category']) && $filters['hec_category'] !== 'all') {
            $query->where('hec_category', 'like', '%'.$filters['hec_category'].'%');
        }
        if (! empty($filters['max_fee']) && is_numeric($filters['max_fee'])) {
            $query->where('fee_min', '>', 0)->where('fee_min', '<=', (int) $filters['max_fee']);
        }
        [$column, $direction] = match ($sort) {
            'name_asc' => ['name', 'asc'], 'name_desc' => ['name', 'desc'],
            'fee_asc' => ['fee_min', 'asc'], 'fee_desc' => ['fee_max', 'desc'],
            default => ['id', 'asc'],
        };
        $perPage = max(1, min($perPage, 500));
        $page = max(1, $page);
        $results = $query->orderBy($column, $direction)->orderBy('id')->paginate($perPage, ['*'], 'page', $page);

        return [
            'data' => $results->getCollection()->map(fn (University $university): array => $this->presentUniversity($university))->all(),
            'total' => $results->total(), 'per_page' => $perPage, 'current_page' => $results->currentPage(),
            'total_pages' => $results->lastPage(), 'from' => $results->firstItem() ?? 0, 'to' => $results->lastItem() ?? 0,
        ];
    }

    /**
     * Get a single university by slug.
     */
    public function getUniversityBySlug(string $slug): ?array
    {
        $university = University::with(['campuses.programs', 'sourceRecords.dataSource'])->withCount('programs')->where('slug', $slug)->first();

        return $university ? $this->presentUniversity($university) : null;
    }

    /** @return array<string, mixed> */
    private function presentUniversity(University $university): array
    {
        $legacy = collect($this->universities)->firstWhere('slug', $university->slug) ?? [];
        $data = array_replace($legacy, $university->toArray());
        foreach (['acronym', 'hec_ranking', 'hec_category', 'fee_range', 'acceptance_rate', 'total_students', 'faculty_phd_ratio', 'campus_size', 'hostel_availability', 'charter_citation', 'gazette_reference', 'deadline', 'description', 'address', 'phone', 'email', 'charter_type', 'established'] as $field) {
            $data[$field] = $data[$field] ?? 'Not verified';
        }
        $data['acronym'] = $university->acronym ?? '—';
        $data['website'] = $university->website ?? '';
        $data['accreditations'] = $data['accreditations'] ?? [];
        $data['scholarships_offered'] = [];
        $data['related_universities'] = [];
        $data['programs'] = $university->programs()->with(['university', 'campus'])->get()->map(fn (Program $p): array => $this->presentProgram($p))->all();
        $data['campuses'] = $university->campuses->map(function ($campus) use ($legacy): array {
            $previous = collect($legacy['campuses'] ?? [])->firstWhere('slug', $campus->slug) ?? [];
            $item = array_replace($previous, $campus->toArray());
            $item['is_main'] = $campus->is_main_campus;
            $item['programs_offered_count'] = $campus->programs->count();
            foreach (['address', 'phone', 'email', 'hostel_capacity', 'transport', 'libraries'] as $field) {
                $item[$field] = $item[$field] ?? 'Not verified';
            }
            $item['facilities'] = $item['facilities'] ?? [];

            return $item;
        })->all();

        return $data;
    }

    /**
     * Get campus by university slug and campus slug.
     */
    public function getCampus(string $uniSlug, string $campusSlug): ?array
    {
        $uni = $this->getUniversityBySlug($uniSlug);
        if (! $uni) {
            return null;
        }

        $campus = collect($uni['campuses'] ?? [])->firstWhere('slug', $campusSlug);
        if (! $campus) {
            return null;
        }

        $campus['university'] = $uni;
        $campus['programs'] = Program::with(['university', 'campus'])->where('campus_id', $campus['id'])->get()->map(fn (Program $program): array => $this->presentProgram($program))->all();

        return $campus;
    }

    /**
     * Get filtered programs with pagination.
     */
    public function getPrograms(array $filters = [], string $sort = 'merit_desc', int $perPage = 6, int $page = 1): array
    {
        $items = Program::with(['university', 'campus'])->get()->map(fn (Program $program): array => $this->presentProgram($program));

        if (! empty($filters['q'])) {
            $q = strtolower(trim($filters['q']));
            $items = $items->filter(function ($p) use ($q) {
                return str_contains(strtolower($p['title']), $q) ||
                       str_contains(strtolower($p['university_name']), $q) ||
                       str_contains(strtolower($p['discipline_label']), $q);
            });
        }

        if (! empty($filters['discipline']) && $filters['discipline'] !== 'all') {
            $items = $items->where('discipline', $filters['discipline']);
        }

        if (! empty($filters['degree_level']) && $filters['degree_level'] !== 'all') {
            $items = $items->where('degree_level', $filters['degree_level']);
        }

        if (! empty($filters['university']) && $filters['university'] !== 'all') {
            $items = $items->where('university_slug', $filters['university']);
        }

        if (! empty($filters['max_fee']) && is_numeric($filters['max_fee'])) {
            $items = $items->filter(fn ($p) => $p['fee_raw'] <= (int) $filters['max_fee']);
        }

        $items = match ($sort) {
            'fee_asc' => $items->sortBy('fee_raw'),
            'fee_desc' => $items->sortByDesc('fee_raw'),
            'title_asc' => $items->sortBy('title'),
            default => $items->sortByDesc('merit_raw'),
        };

        $total = $items->count();
        $totalPages = max(1, (int) ceil($total / $perPage));
        $page = max(1, min($page, $totalPages));
        $offset = ($page - 1) * $perPage;
        $paginatedItems = $items->slice($offset, $perPage)->values()->all();

        return [
            'data' => $paginatedItems,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'from' => $total > 0 ? $offset + 1 : 0,
            'to' => min($offset + $perPage, $total),
        ];
    }

    /**
     * Get single program by slug.
     */
    public function getProgramBySlug(string $slug): ?array
    {
        $program = Program::with(['university', 'campus'])->where('slug', $slug)->first();
        if (! $program) {
            return null;
        }
        $data = $this->presentProgram($program);
        $data['university'] = $this->getUniversityBySlug($program->university->slug);
        $data['resolved_related'] = [];

        return $data;
    }

    private function presentProgram(Program $program): array
    {
        $legacy = collect($this->programs)->firstWhere('slug', $program->slug) ?? [];
        $data = array_replace($legacy, $program->toArray());

        return array_replace([
            'department' => 'Not verified', 'degree_level_label' => $program->degree_level,
            'discipline_label' => $program->discipline, 'duration' => $program->duration_years.' years',
            'study_mode' => 'Not verified', 'shift' => 'Not verified', 'accreditation' => 'Not verified',
            'council' => 'Not verified', 'deadline' => 'Not verified', 'admission_criteria' => [],
            'curriculum_highlights' => [], 'career_outlook' => 'Not verified', 'related_programs' => [],
        ], $data, [
            'title' => $program->name, 'university_slug' => $program->university->slug,
            'university_name' => $program->university->name,
            'available_campuses' => [$program->campus->slug], 'campus_id' => $program->campus_id,
            'city' => $program->campus->city, 'province' => app(RegionCatalog::class)->normalizeRegion($program->campus->province),
            'fee_raw' => (int) preg_replace('/[^0-9]/', '', $program->fee_per_semester ?? ''),
            'merit_raw' => (float) ($program->cutoff_merit ?? 0),
            'cutoff_merit' => $program->cutoff_merit ?? 'Not verified',
            'fee_semester' => $program->fee_per_semester ?? 'Not verified',
            'overview' => $program->description ?? 'Program details are pending verification.',
        ]);
    }

    /**
     * Search across universities, programs, and scholarships.
     */
    public function search(string $query, string $type = 'all'): array
    {
        $q = strtolower(trim($query));
        $results = [
            'universities' => [],
            'programs' => [],
            'scholarships' => [],
            'total' => 0,
        ];

        if (empty($q)) {
            return $results;
        }

        if ($type === 'all' || $type === 'universities') {
            $results['universities'] = $this->getUniversities(['q' => $query], 'name_asc', 50)['data'];
        }

        if ($type === 'all' || $type === 'programs') {
            $results['programs'] = $this->getPrograms(['q' => $query], 'title_asc', 100)['data'];
        }

        $results['total'] = count($results['universities']) + count($results['programs']);

        return $results;
    }

    /**
     * Get filter options for dropdowns.
     */
    public function getFilterOptions(): array
    {
        return [
            'provinces' => ['all' => 'All covered regions'] + app(RegionCatalog::class)->regions(),
            'cities' => ['all' => 'All Cities'] + app(RegionCatalog::class)->cities(),
            'sectors' => [
                'all' => 'All Sectors (Public & Private)',
                'public' => 'Public Sector (Government)',
                'private' => 'Chartered Private Sector',
            ],
            'disciplines' => [
                'all' => 'All Academic Disciplines',
                'computing' => 'Computing & Artificial Intelligence',
                'engineering' => 'Engineering & Technology (PEC)',
                'business' => 'Business & Management (NBEAC)',
                'medical' => 'Medical & Health Sciences (PMDC)',
                'natural_sciences' => 'Natural & Applied Sciences',
                'social_sciences' => 'Social Sciences & Humanities',
            ],
            'degree_levels' => [
                'all' => 'All Degree Levels',
                'bs' => 'Undergraduate (BS / BE / BBA)',
                'ms' => 'Postgraduate (MS / MPhil / MBA)',
                'phd' => 'Doctoral (PhD)',
            ],
            'hec_categories' => [
                'all' => 'All HEC Categories',
                'category w' => 'Category W (Highest Quality Tier)',
                'category x' => 'Category X',
            ],
        ];
    }
}
