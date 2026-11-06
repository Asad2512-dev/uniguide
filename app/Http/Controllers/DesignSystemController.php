<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DesignSystemController extends Controller
{
    /**
     * Display the comprehensive living design system showcase.
     */
    public function index(): View
    {
        $demonstrationUniversities = [
            [
                'name' => 'National University of Sciences & Technology',
                'acronym' => 'NUST',
                'location' => 'Islamabad, Federal Capital',
                'sector' => 'Public Sector',
                'hecRank' => '#1 Overall Engineering & Tech',
                'feeRange' => 'PKR 135,000 – 175,000',
                'programsCount' => '54 Degree Programs',
                'acceptanceRate' => '~12% Highly Selective',
                'status' => 'open',
                'deadline' => '15 July 2026',
                'featured' => true,
            ],
            [
                'name' => 'Lahore University of Management Sciences',
                'acronym' => 'LUMS',
                'location' => 'Lahore, Punjab',
                'sector' => 'Chartered Private',
                'hecRank' => '#1 Business & Management',
                'feeRange' => 'PKR 380,000 – 460,000',
                'programsCount' => '32 Degree Programs',
                'acceptanceRate' => '~9% Extremely Selective',
                'status' => 'closing_soon',
                'deadline' => '30 June 2026',
                'featured' => false,
            ],
            [
                'name' => 'FAST - National University of Computer & Emerging Sciences',
                'acronym' => 'FAST',
                'location' => 'Islamabad & Karachi Campuses',
                'sector' => 'Chartered Private',
                'hecRank' => '#1 Computing & IT',
                'feeRange' => 'PKR 145,000 – 165,000',
                'programsCount' => '24 Degree Programs',
                'acceptanceRate' => '~15% Competitive',
                'status' => 'open',
                'deadline' => '05 July 2026',
                'featured' => false,
            ],
            [
                'name' => 'Ghulam Ishaq Khan Institute (GIKI)',
                'acronym' => 'GIKI',
                'location' => 'Topi, Khyber Pakhtunkhwa',
                'sector' => 'Chartered Autonomous',
                'hecRank' => 'Top 3 Engineering',
                'feeRange' => 'PKR 340,000 – 410,000',
                'programsCount' => '18 Degree Programs',
                'acceptanceRate' => '~14% Competitive',
                'status' => 'merit_announced',
                'deadline' => 'List Declared',
                'featured' => false,
            ],
        ];

        $demonstrationPrograms = [
            [
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
                'title' => 'BE Electrical Engineering',
                'university' => 'NUST SEECS Islamabad',
                'degreeLevel' => 'Undergraduate (BE)',
                'duration' => '4 Years • 8 Semesters',
                'feePerSemester' => 'PKR 135,000',
                'cutoffMerit' => '74.6% Aggregate',
                'accreditation' => 'Pakistan Engineering Council (PEC) • Washington Accord',
                'status' => 'open',
            ],
            [
                'title' => 'BSc (Hons) Accounting & Finance',
                'university' => 'LUMS SDSB Lahore',
                'degreeLevel' => 'Undergraduate (BS)',
                'duration' => '4 Years • 8 Semesters',
                'feePerSemester' => 'PKR 410,000',
                'cutoffMerit' => 'SAT 1380+ / LCAT',
                'accreditation' => 'AACSB Accredited • HEC Category W',
                'status' => 'closing_soon',
            ],
        ];

        $demonstrationScholarships = [
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
        ];

        return view('pages.design-system.index', [
            'universities' => $demonstrationUniversities,
            'programs' => $demonstrationPrograms,
            'scholarships' => $demonstrationScholarships,
        ]);
    }
}
