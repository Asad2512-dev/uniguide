<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Navigation Configuration
    |--------------------------------------------------------------------------
    |
    | Centralized configuration for all layout families in UniGuide Pakistan.
    | Provides clean route mapping, active state matching, icons, and badges.
    |
    */

    'public' => [
        [
            'label' => 'AI Counselor',
            'route' => 'counselor.index',
            'icon' => 'sparkles',
            'badge' => 'Intelligent',
            'badge_color' => 'brand',
            'description' => 'Personalized admission advisor, aggregate evaluation, and conversational search.',
        ],
        [
            'label' => 'Explore Universities',
            'route' => 'universities.index',
            'icon' => 'building-2',
            'description' => 'Browse 248+ HEC-recognized public and chartered institutions across Pakistan.',
        ],
        [
            'label' => 'Degree Programs',
            'route' => 'programs.index',
            'icon' => 'graduation-cap',
            'description' => 'Search undergraduate and graduate degrees with merit formulas and fee structures.',
        ],
        [
            'label' => 'Admissions & Deadlines',
            'route' => 'admissions.index',
            'icon' => 'calendar-clock',
            'badge' => 'Fall 2026',
            'description' => 'Track open intakes, national entry test dates, and application cutoffs.',
        ],
        [
            'label' => 'Scholarships',
            'route' => 'scholarships.index',
            'icon' => 'award',
            'description' => 'Discover need-based and merit scholarships including HEC, Ehsaas, and provincial endowments.',
        ],
        [
            'label' => 'Compare Universities',
            'route' => 'compare.index',
            'icon' => 'git-compare',
            'badge' => 'Side-by-Side',
            'description' => 'Compare up to 4 Pakistani universities across verified fees, merit formulas, and rankings.',
        ],
        [
            'label' => 'Eligibility Checker',
            'route' => 'eligibility.index',
            'icon' => 'check-circle',
            'badge' => 'Rules Engine',
            'description' => 'Check your eligibility against PEC, NCEAC, and university admission statutes.',
        ],
        [
            'label' => 'Platform Features',
            'route' => 'marketing.features',
            'icon' => 'sparkles',
            'description' => 'Merit engine, cutoff forecaster, side-by-side comparison, and document vault.',
        ],
        [
            'label' => 'How It Works',
            'route' => 'marketing.how_it_works',
            'icon' => 'compass',
            'description' => '4-step guide from aggregate calculation to fee challan submission.',
        ],
        [
            'label' => 'About UniGuide',
            'route' => 'marketing.about',
            'icon' => 'shield-check',
            'description' => 'Our national charter, HEC data transparency, mission, and leadership.',
        ],
    ],

    'student' => [
        'main' => [
            [
                'label' => 'Dashboard Overview',
                'route' => 'student.dashboard',
                'icon' => 'layout-dashboard',
                'active' => 'student.dashboard',
            ],
            [
                'label' => 'Admission Tracker',
                'route' => 'student.tracker',
                'icon' => 'file-text',
                'badge' => '5 Active',
                'badge_color' => 'brand',
                'active' => 'student.tracker*',
            ],
            [
                'label' => 'Recommendations',
                'route' => 'student.recommendations',
                'icon' => 'compass',
                'badge' => 'Tiers Ready',
                'badge_color' => 'success',
                'active' => 'student.recommendations*',
            ],
            [
                'label' => 'Saved Universities',
                'route' => 'student.saved.universities',
                'icon' => 'building-2',
                'badge' => '4',
                'active' => 'student.saved.universities*',
            ],
            [
                'label' => 'Saved Programs',
                'route' => 'student.saved.programs',
                'icon' => 'bookmark',
                'badge' => '3',
                'active' => 'student.saved.programs*',
            ],
            [
                'label' => 'Saved Scholarships',
                'route' => 'student.saved.scholarships',
                'icon' => 'award',
                'badge' => '3',
                'active' => 'student.saved.scholarships*',
            ],
            [
                'label' => 'AI Admission Advisor',
                'route' => 'counselor.index',
                'icon' => 'sparkles',
                'badge' => 'Online',
                'badge_color' => 'success',
                'active' => 'counselor.*',
            ],
        ],
        'secondary' => [
            [
                'label' => 'Academic Profile',
                'route' => 'student.profile',
                'icon' => 'user-check',
                'badge' => '88%',
                'badge_color' => 'brand',
                'active' => 'student.profile*',
            ],
            [
                'label' => 'Notifications',
                'route' => 'student.notifications',
                'icon' => 'bell',
                'badge' => '2 New',
                'badge_color' => 'warning',
                'active' => 'student.notifications*',
            ],
            [
                'label' => 'Account Settings',
                'route' => 'student.settings',
                'icon' => 'settings',
                'active' => 'student.settings*',
            ],
        ],
    ],

    'university_admin' => [
        'main' => [
            [
                'label' => 'Institutional Console',
                'route' => 'preview.university_admin',
                'icon' => 'layout-dashboard',
                'active' => 'preview.university_admin',
            ],
            [
                'label' => 'Programs & Curricula',
                'route' => 'preview.university_admin',
                'icon' => 'book-open',
                'badge' => '42 Active',
            ],
            [
                'label' => 'Admission Cycles',
                'route' => 'preview.university_admin',
                'icon' => 'calendar-range',
                'badge' => 'Fall 2026 Open',
                'badge_color' => 'success',
            ],
            [
                'label' => 'Merit Lists & Cutoffs',
                'route' => 'preview.university_admin',
                'icon' => 'list-ordered',
            ],
            [
                'label' => 'Applicant Inquiries',
                'route' => 'preview.university_admin',
                'icon' => 'message-square',
                'badge' => '14 New',
                'badge_color' => 'brand',
            ],
            [
                'label' => 'Fee Schedules & Quotas',
                'route' => 'preview.university_admin',
                'icon' => 'receipt',
            ],
        ],
        'secondary' => [
            [
                'label' => 'HEC Compliance & Audits',
                'route' => 'preview.university_admin',
                'icon' => 'shield-check',
                'badge' => 'Verified',
                'badge_color' => 'success',
            ],
            [
                'label' => 'Campus & Facilities',
                'route' => 'preview.university_admin',
                'icon' => 'building',
            ],
            [
                'label' => 'Institutional Settings',
                'route' => 'preview.university_admin',
                'icon' => 'sliders-horizontal',
            ],
        ],
    ],

    'national_admin' => [
        'main' => [
            [
                'label' => 'National HEC Command',
                'route' => 'preview.national_admin',
                'icon' => 'layout-dashboard',
                'active' => 'preview.national_admin',
            ],
            [
                'label' => 'University Registry',
                'route' => 'preview.national_admin',
                'icon' => 'building-2',
                'badge' => '248 Recognized',
            ],
            [
                'label' => 'Accreditation & Charters',
                'route' => 'preview.national_admin',
                'icon' => 'file-badge-2',
                'badge' => '6 Pending Review',
                'badge_color' => 'warning',
            ],
            [
                'label' => 'National Merit Monitoring',
                'route' => 'preview.national_admin',
                'icon' => 'trending-up',
            ],
            [
                'label' => 'Scholarship Disbursal',
                'route' => 'preview.national_admin',
                'icon' => 'coins',
            ],
            [
                'label' => 'Fraud & Warning Alerts',
                'route' => 'preview.national_admin',
                'icon' => 'alert-triangle',
                'badge' => '1 Unaccredited',
                'badge_color' => 'error',
            ],
        ],
        'secondary' => [
            [
                'label' => 'System Audit Trail',
                'route' => 'preview.national_admin',
                'icon' => 'history',
            ],
            [
                'label' => 'Federal Data Sync',
                'route' => 'preview.national_admin',
                'icon' => 'refresh-cw',
                'badge' => 'Synced',
                'badge_color' => 'success',
            ],
            [
                'label' => 'Platform Governance',
                'route' => 'preview.national_admin',
                'icon' => 'lock',
            ],
        ],
    ],
];
