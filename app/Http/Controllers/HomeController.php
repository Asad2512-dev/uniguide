<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the UniGuide Pakistan Homepage.
     */
    public function index(): View
    {
        $featuredUniversities = [
            [
                'slug' => 'nust',
                'name' => 'National University of Sciences & Technology',
                'acronym' => 'NUST',
                'location' => 'Sector H-12, Islamabad',
                'sector' => 'Public Sector',
                'hecRank' => '#1 Engineering & Technology',
                'feeRange' => 'PKR 135,000 – 175,000',
                'programsCount' => '54 Degree Programs',
                'acceptanceRate' => '~12% Highly Selective',
                'status' => 'open',
                'deadline' => '15 July 2026',
                'featured' => true,
                'category' => 'Engineering & IT',
                'province' => 'Islamabad (Federal)',
            ],
            [
                'slug' => 'fast-nuces',
                'name' => 'FAST - National University of Computer & Emerging Sciences',
                'acronym' => 'FAST',
                'location' => 'Islamabad, Lahore & Karachi Campuses',
                'sector' => 'Chartered Autonomous',
                'hecRank' => '#1 Computing & AI',
                'feeRange' => 'PKR 145,000 – 165,000',
                'programsCount' => '24 Degree Programs',
                'acceptanceRate' => '~15% Competitive',
                'status' => 'open',
                'deadline' => '05 July 2026',
                'featured' => true,
                'category' => 'Computing & IT',
                'province' => 'Multi-Campus',
            ],
            [
                'slug' => 'lums',
                'name' => 'Lahore University of Management Sciences',
                'acronym' => 'LUMS',
                'location' => 'DHA Phase 5, Lahore, Punjab',
                'sector' => 'Chartered Private',
                'hecRank' => '#1 Business & Management',
                'feeRange' => 'PKR 380,000 – 460,000',
                'programsCount' => '32 Degree Programs',
                'acceptanceRate' => '~9% Extremely Selective',
                'status' => 'closing_soon',
                'deadline' => '30 June 2026',
                'featured' => true,
                'category' => 'Business & Social Sciences',
                'province' => 'Punjab',
            ],
            [
                'slug' => 'giki',
                'name' => 'Ghulam Ishaq Khan Institute (GIKI)',
                'acronym' => 'GIKI',
                'location' => 'Topi, Swabi, Khyber Pakhtunkhwa',
                'sector' => 'Chartered Autonomous',
                'hecRank' => 'Top 3 Engineering (PEC Level-II)',
                'feeRange' => 'PKR 340,000 – 410,000',
                'programsCount' => '18 Degree Programs',
                'acceptanceRate' => '~14% Competitive',
                'status' => 'merit_announced',
                'deadline' => '1st List Active',
                'featured' => false,
                'category' => 'Engineering & Tech',
                'province' => 'KPK',
            ],
            [
                'slug' => 'iba-karachi',
                'name' => 'Institute of Business Administration (IBA)',
                'acronym' => 'IBA',
                'location' => 'University Road & City Campus, Karachi',
                'sector' => 'Public Chartered (Sindh Act)',
                'hecRank' => '#1 Premier Sindh Business School',
                'feeRange' => 'PKR 290,000 – 360,000',
                'programsCount' => '22 Degree Programs',
                'acceptanceRate' => '~11% Highly Selective',
                'status' => 'open',
                'deadline' => '12 July 2026',
                'featured' => false,
                'category' => 'Business & CS',
                'province' => 'Sindh',
            ],
            [
                'slug' => 'aku',
                'name' => 'Aga Khan University (AKU)',
                'acronym' => 'AKU',
                'location' => 'Stadium Road, Karachi, Sindh',
                'sector' => 'Chartered Autonomous',
                'hecRank' => '#1 Health Sciences & Medicine (PMDC)',
                'feeRange' => 'PKR 540,000 – 720,000',
                'programsCount' => '16 Medical Programs',
                'acceptanceRate' => '~4% Elite Medical',
                'status' => 'closing_soon',
                'deadline' => '25 June 2026',
                'featured' => false,
                'category' => 'Medical & Health',
                'province' => 'Sindh',
            ],
        ];

        $popularPrograms = [
            [
                'slug' => 'fast-bs-computer-science',
                'title' => 'BS Computer Science (BSCS)',
                'university' => 'FAST-NUCES Islamabad',
                'degreeLevel' => 'Undergraduate (BS)',
                'duration' => '4 Years • 8 Semesters',
                'feePerSemester' => 'PKR 145,000',
                'cutoffMerit' => '78.2% Aggregate',
                'accreditation' => 'NCEAC Category W • Computing Council',
                'status' => 'open',
            ],
            [
                'slug' => 'nust-bs-software-engineering',
                'title' => 'BS Software Engineering',
                'university' => 'NUST SEECS Islamabad',
                'degreeLevel' => 'Undergraduate (BS)',
                'duration' => '4 Years • 8 Semesters',
                'feePerSemester' => 'PKR 135,000',
                'cutoffMerit' => '80.12% Aggregate',
                'accreditation' => 'NCEAC & HEC Recognized',
                'status' => 'open',
            ],
            [
                'slug' => 'uet-be-mechanical-engineering',
                'title' => 'BE Mechanical Engineering',
                'university' => 'UET Lahore Main Campus',
                'degreeLevel' => 'Undergraduate (BE)',
                'duration' => '4 Years • 8 Semesters',
                'feePerSemester' => 'PKR 78,000',
                'cutoffMerit' => '73.40% ECAT Aggregate',
                'accreditation' => 'PEC Level-II (Washington Accord)',
                'status' => 'upcoming',
            ],
            [
                'slug' => 'iba-bba-honors',
                'title' => 'Bachelor of Business Administration (BBA)',
                'university' => 'IBA Karachi',
                'degreeLevel' => 'Undergraduate (BBA Hons)',
                'duration' => '4 Years • 8 Semesters',
                'feePerSemester' => 'PKR 310,000',
                'cutoffMerit' => 'IBA Test Aptitude Cutoff',
                'accreditation' => 'NBEAC W-Category • HEC',
                'status' => 'open',
            ],
            [
                'slug' => 'giki-bs-artificial-intelligence',
                'title' => 'BS Artificial Intelligence',
                'university' => 'GIKI Topi, KPK',
                'degreeLevel' => 'Undergraduate (BS)',
                'duration' => '4 Years • 8 Semesters',
                'feePerSemester' => 'PKR 360,000',
                'cutoffMerit' => '78.90% Aggregate',
                'accreditation' => 'NCEAC Approved Curriculum',
                'status' => 'merit_announced',
            ],
            [
                'slug' => 'aku-mbbs',
                'title' => 'Bachelor of Medicine & Surgery (MBBS)',
                'university' => 'Aga Khan University (AKU) Karachi',
                'degreeLevel' => 'Professional (MBBS)',
                'duration' => '5 Years • Clinical Rotations',
                'feePerSemester' => 'PKR 540,000',
                'cutoffMerit' => 'AKU Test + MDCAT (PMDC)',
                'accreditation' => 'PMDC Recognized Level-I',
                'status' => 'closing_soon',
            ],
        ];

        $scholarships = [
            [
                'title' => 'HEC Need-Based Undergraduate Scholarship',
                'provider' => 'Higher Education Commission of Pakistan',
                'coverage' => '100% Tuition Fee + PKR 6,000/mo Living Stipend',
                'eligibility' => 'Family income under PKR 45,000/mo • Enrolled in participating public university',
                'deadline' => '30 August 2026',
                'category' => 'Need & Merit Based',
            ],
            [
                'title' => 'Ehsaas National Undergraduate Scholarship Phase-IV',
                'provider' => 'Ministry of Poverty Alleviation & Social Safety',
                'coverage' => 'Full Tuition Waiver + Annual Maintenance Grant',
                'eligibility' => 'Underprivileged students with high matric/FSc merit from targeted districts',
                'deadline' => '15 September 2026',
                'category' => 'National Equity Grant',
            ],
            [
                'title' => 'USAID-Funded Merit & Needs-Based Scholarship',
                'provider' => 'HEC Pakistan in collaboration with USAID',
                'coverage' => 'Complete Degree Tuition + Books + Transport Stipend',
                'eligibility' => '50% female student quota • Agriculture, Engineering, Medicine, & Social Sciences',
                'deadline' => '10 August 2026',
                'category' => 'Bilateral Development Grant',
            ],
            [
                'title' => 'Punjab Educational Endowment Fund (PEEF) Master & BS',
                'provider' => 'Govt. of Punjab & PEEF Trust',
                'coverage' => 'Tuition Reimbursement + Monthly Boarding Allowance',
                'eligibility' => 'Domicile in Punjab • 60%+ marks in Intermediate • Special quotas for orphans',
                'deadline' => '20 September 2026',
                'category' => 'Provincial Endowment',
            ],
        ];

        $comparisonMatrix = [
            'institutions' => [
                ['name' => 'NUST Islamabad', 'type' => 'Public Sector', 'acronym' => 'NUST', 'badge' => 'HEC #1 Overall'],
                ['name' => 'FAST-NUCES', 'type' => 'Chartered Autonomous', 'acronym' => 'FAST', 'badge' => 'HEC #1 Computing'],
                ['name' => 'LUMS Lahore', 'type' => 'Chartered Private', 'acronym' => 'LUMS', 'badge' => 'HEC #1 Business'],
            ],
            'attributes' => [
                ['label' => 'Degree Level Verification', 'nust' => 'HEC Recognized Category W', 'fast' => 'HEC Recognized Category W', 'lums' => 'HEC Recognized Category W'],
                ['label' => 'BSCS Semester Tuition Fee', 'nust' => 'PKR 135,000', 'fast' => 'PKR 145,000', 'lums' => 'PKR 410,000'],
                ['label' => 'Standard Admission Test', 'nust' => 'NET (NUST Entry Test)', 'fast' => 'NU Test or SAT/NTS', 'lums' => 'LCAT or SAT I'],
                ['label' => 'Weightage Formula', 'nust' => '75% NET + 15% FSc + 10% Matric', 'fast' => '50% Test + 50% FSc Part-I', 'lums' => 'Holistic (SAT + Grades + ECs)'],
                ['label' => 'Accreditation Bodies', 'nust' => 'PEC (Level-II) • NCEAC • NBEAC', 'fast' => 'NCEAC (Top Rank) • PEC', 'lums' => 'AACSB Accredited • NCEAC'],
                ['label' => 'On-Campus Hostels', 'nust' => 'Available for Outstation Boys & Girls', 'fast' => 'Limited Private Arrangements', 'lums' => 'Guaranteed for 1st Year Students'],
                ['label' => 'Need-Based Aid Ratio', 'nust' => '~35% of Students Receive Aid', 'fast' => '~20% Need & Merit Scholarships', 'lums' => '1 in 3 Students on Financial Aid'],
            ],
        ];

        return view('pages.marketing.home', [
            'featuredUniversities' => $featuredUniversities,
            'popularPrograms' => $popularPrograms,
            'scholarships' => $scholarships,
            'comparisonMatrix' => $comparisonMatrix,
        ]);
    }
}

