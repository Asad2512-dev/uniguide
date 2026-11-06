<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MarketingController extends Controller
{
    /**
     * Display the About UniGuide Pakistan screen.
     */
    public function about(): View
    {
        $leadership = [
            [
                'name' => 'Prof. Dr. Tariq Mansoor',
                'role' => 'Chair, National Higher Education Advisory Council',
                'credentials' => 'Former Vice Chancellor • PhD MIT • 30+ Years Higher Ed Governance',
                'avatar' => 'TM',
            ],
            [
                'name' => 'Dr. Ayesha Siddiqui',
                'role' => 'Chief Academic Systems Architect & Data Registrar',
                'credentials' => 'Ex-HEC Quality Assurance Directorate • PhD Cambridge • Curriculum Standardization',
                'avatar' => 'AS',
            ],
            [
                'name' => 'Zubair Ahmad Farooqi',
                'role' => 'Director of Institutional Relations & Provincial Coordination',
                'credentials' => 'Liaison to Provincial Higher Education Commissions (PHEC, SEDA, KP-HED)',
                'avatar' => 'ZF',
            ],
        ];

        $charterMilestones = [
            ['year' => '2023', 'title' => 'National Higher Ed Informatics Initiative', 'description' => 'Conceptualized in Islamabad to eliminate fragmented prospectus databases and unverified cutoff myths.'],
            ['year' => '2024', 'title' => 'Federal & Provincial Registry Direct Sync', 'description' => 'Integrated official accreditation and fee schedules for 248 institutions across all 5 administrative territories.'],
            ['year' => '2025', 'title' => 'AI Admissions & Merit Prediction Engine', 'description' => 'Trained on 7 years of published university merit gazettes and BISE intermediate score distributions.'],
            ['year' => '2026', 'title' => 'UniGuide 1.0 Commercial National Release', 'description' => 'Unified discovery, multi-university application tracking, and automated 1-Link bank challan issuance.'],
        ];

        return view('pages.marketing.about', [
            'leadership' => $leadership,
            'charterMilestones' => $charterMilestones,
        ]);
    }

    /**
     * Display the How It Works screen.
     */
    public function howItWorks(): View
    {
        $workflowSteps = [
            [
                'step' => '01',
                'title' => 'Discover & Filter Accredited Institutions',
                'subtitle' => 'Zero Unaccredited Sub-Campuses',
                'description' => 'Search by province, academic discipline, tuition fee range, or entry test requirements. All data is verified against the Higher Education Commission Ordinance and gazetted statutory charters.',
                'features' => ['Filter by PEC, PMDC, NCEAC councils', 'Live fee schedules without hidden charges', 'Verified on-campus hostel availability'],
                'icon' => 'building-2',
            ],
            [
                'step' => '02',
                'title' => 'Input Academic Credentials Once',
                'subtitle' => 'Matric, FSc / A-Levels & Entry Tests',
                'description' => 'Enter your BISE roll number or A-Level equivalent grades. The platform standardizes your marks and automatically calculates your exact aggregate percentiles for over 40 distinct institutional admission formulas.',
                'features' => ['NUST NET 75/15/10 weightage', 'FAST NU 50/50 formula', 'MDCAT & ECAT provincial quotas'],
                'icon' => 'calculator',
            ],
            [
                'step' => '03',
                'title' => 'Receive AI Guidance & Cutoff Forecasts',
                'subtitle' => 'Evidence-Based Admission Probabilities',
                'description' => 'Our reasoning model benchmarks your calculated score against the last 5 years of closing merit lists, factoring in test difficulty shifts and seat quota distributions.',
                'features' => ['First vs second vs third list probabilities', 'Alternative high-match degree recommendations', '100% cited university prospectus sources'],
                'icon' => 'sparkles',
            ],
            [
                'step' => '04',
                'title' => 'Track Applications & Pay Fee Challans',
                'subtitle' => 'Unified Admissions Document Vault',
                'description' => 'Submit applications directly or follow institution portals with real-time status notifications. Generate 1-Link bank challan numbers payable at any Pakistani commercial bank or mobile wallet.',
                'features' => ['Automated deadline countdowns', 'Central document locker for CNIC & Domicile', 'Official merit list announcement alerts'],
                'icon' => 'receipt',
            ],
        ];

        return view('pages.marketing.how-it-works', [
            'workflowSteps' => $workflowSteps,
        ]);
    }

    /**
     * Display the Platform Features screen.
     */
    public function features(): View
    {
        $featureModules = [
            [
                'badge' => 'Discovery Engine',
                'title' => 'Intelligent Multi-Parameter University Search',
                'description' => 'Find institutions by exact criteria: public vs private sector, PEC accreditation tier, maximum semester tuition, hostel availability, and HEC category ranking.',
                'icon' => 'search',
                'highlights' => ['Live search with instant keyboard shortcuts (⌘K)', 'Provincial jurisdiction filters (ICT, Punjab, Sindh, KPK, Balochistan)', 'Zero unverified universities or fraudulent programs'],
            ],
            [
                'badge' => 'Merit Intelligence',
                'title' => 'Algorithmic Aggregate Merit Calculator',
                'description' => 'Never struggle with complex university formula calculations. UniGuide automatically applies each university’s unique published weightage.',
                'icon' => 'calculator',
                'highlights' => ['Covers NUST NET, FAST NU, UET ECAT, NUMS & MDCAT', 'Calculates matric, intermediate Part-I, and test proportions', 'Interactive percentile distribution graphs'],
            ],
            [
                'badge' => 'Comparison Matrix',
                'title' => 'Side-by-Side Institutional Comparison',
                'description' => 'Compare up to three universities or degree programs simultaneously across 18 critical parameters.',
                'icon' => 'scale',
                'highlights' => ['Total 4-year degree cost comparison', 'Faculty PhD ratio and research funding index', 'Alumni employment & sector placement insights'],
            ],
            [
                'badge' => 'Financial Aid',
                'title' => 'National Scholarship & Endowment Matcher',
                'description' => 'Instantly match your family income, domicile, and academic merit with over PKR 4.2B in available national grants.',
                'icon' => 'award',
                'highlights' => ['Ehsaas Undergraduate Phase-IV criteria engine', 'HEC Need-Based Grants for public sector', 'Provincial PEEF and USAID funding guidelines'],
            ],
            [
                'badge' => 'Admissions Advisor',
                'title' => 'Verified AI Academic Counseling',
                'description' => 'An AI advisor grounded strictly in Pakistani statutory higher education regulations. Zero hallucinations.',
                'icon' => 'sparkles',
                'highlights' => ['Every response includes official gazette citations', 'Safe vs target vs reach admission categorizations', 'Personalized career discipline alignment advice'],
            ],
            [
                'badge' => 'Document Vault',
                'title' => 'Secure Student Document Locker',
                'description' => 'Store your verified BISE marksheets, domicile certificates, CNIC copies, and entry test admit cards securely in one cloud vault.',
                'icon' => 'folder-lock',
                'highlights' => ['Bank-grade encryption for national identity docs', 'Instant reuse across multiple university applications', 'Prevents duplicate notarization expenses'],
            ],
        ];

        return view('pages.marketing.features', [
            'featureModules' => $featureModules,
        ]);
    }

    /**
     * Display the Contact and Support screen.
     */
    public function contact(): View
    {
        $regionalDesks = [
            [
                'region' => 'Federal & ICT Headquarters',
                'address' => 'Higher Education Complex, Sector H-9/1, Islamabad',
                'phone' => '+92 (051) 9040-2026',
                'email' => 'admissions.ict@uniguide.pk',
                'hours' => 'Mon – Fri: 9:00 AM – 5:00 PM PKT',
            ],
            [
                'region' => 'Punjab Regional Coordination',
                'address' => 'Mall Road Academic Secretariat, Lahore',
                'phone' => '+92 (042) 3578-2026',
                'email' => 'punjab.desk@uniguide.pk',
                'hours' => 'Mon – Fri: 9:00 AM – 5:00 PM PKT',
            ],
            [
                'region' => 'Sindh Regional Coordination',
                'address' => 'Clifton Block 5 / University Road Hub, Karachi',
                'phone' => '+92 (021) 3496-2026',
                'email' => 'sindh.desk@uniguide.pk',
                'hours' => 'Mon – Fri: 9:00 AM – 5:00 PM PKT',
            ],
            [
                'region' => 'KPK & Balochistan Desks',
                'address' => 'University Town Peshawar & Sariab Road Quetta',
                'phone' => '+92 (091) 5842-2026',
                'email' => 'regional.support@uniguide.pk',
                'hours' => 'Mon – Fri: 9:00 AM – 5:00 PM PKT',
            ],
        ];

        return view('pages.marketing.contact', [
            'regionalDesks' => $regionalDesks,
        ]);
    }

    /**
     * Process contact and support inquiry submission.
     */
    public function submitContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'subject' => ['required', 'string', 'max:150'],
            'category' => ['required', 'string'],
            'message' => ['required', 'string', 'min:20', 'max:2000'],
        ]);

        return redirect()->route('marketing.contact')->with('success', 'Your inquiry has been logged with UniGuide National Support (#UG-TKT-'.rand(10000, 99999).'). Our admissions counselor will contact you within 24 hours.');
    }
}
