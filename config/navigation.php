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
                'route' => 'uni_admin.dashboard',
                'icon' => 'layout-dashboard',
                'active' => 'uni_admin.dashboard',
            ],
            [
                'label' => 'University Profile',
                'route' => 'uni_admin.profile',
                'icon' => 'building-2',
                'active' => 'uni_admin.profile*',
            ],
            [
                'label' => 'Campus Management',
                'route' => 'uni_admin.campuses',
                'icon' => 'map-pin',
                'active' => 'uni_admin.campuses*',
            ],
            [
                'label' => 'Academic Departments',
                'route' => 'uni_admin.departments',
                'icon' => 'graduation-cap',
                'active' => 'uni_admin.departments*',
            ],
            [
                'label' => 'Programs & Degrees',
                'route' => 'uni_admin.programs',
                'icon' => 'book-open',
                'badge' => '42 Active',
                'active' => 'uni_admin.programs*',
            ],
            [
                'label' => 'Fee Schedules',
                'route' => 'uni_admin.fees',
                'icon' => 'receipt',
                'active' => 'uni_admin.fees*',
            ],
            [
                'label' => 'Admission Cycles',
                'route' => 'uni_admin.admissions',
                'icon' => 'calendar-range',
                'badge' => 'Fall 2026',
                'badge_color' => 'success',
                'active' => 'uni_admin.admissions*',
            ],
            [
                'label' => 'Eligibility & Merit',
                'route' => 'uni_admin.eligibility',
                'icon' => 'calculator',
                'active' => 'uni_admin.eligibility*',
            ],
            [
                'label' => 'Scholarships & Aid',
                'route' => 'uni_admin.scholarships',
                'icon' => 'award',
                'active' => 'uni_admin.scholarships*',
            ],
        ],
        'secondary' => [
            [
                'label' => 'AI Data Verification',
                'route' => 'uni_admin.ai_verification',
                'icon' => 'sparkles',
                'badge' => '4 Pending',
                'badge_color' => 'warning',
                'active' => 'uni_admin.ai_verification*',
            ],
            [
                'label' => 'Student Inquiries',
                'route' => 'uni_admin.inquiries',
                'icon' => 'message-square',
                'badge' => '14 New',
                'badge_color' => 'brand',
                'active' => 'uni_admin.inquiries*',
            ],
            [
                'label' => 'Document Verification',
                'route' => 'uni_admin.documents',
                'icon' => 'file-check',
                'active' => 'uni_admin.documents*',
            ],
            [
                'label' => 'Staff & Settings',
                'route' => 'uni_admin.settings',
                'icon' => 'sliders-horizontal',
                'active' => 'uni_admin.settings*',
            ],
        ],
    ],

    'national_admin' => [
        'main' => [
            [
                'label' => 'National Operations',
                'route' => 'national_admin.dashboard',
                'icon' => 'layout-dashboard',
                'active' => 'national_admin.dashboard',
            ],
            [
                'label' => 'University Registry',
                'route' => 'national_admin.universities',
                'icon' => 'building-2',
                'badge' => '248 Chartered',
                'active' => 'national_admin.universities*',
            ],
            [
                'label' => 'Charter Approvals',
                'route' => 'national_admin.approvals',
                'icon' => 'file-badge-2',
                'badge' => '3 Pending',
                'badge_color' => 'warning',
                'active' => 'national_admin.approvals*',
            ],
            [
                'label' => 'Program Registry',
                'route' => 'national_admin.programs',
                'icon' => 'book-open',
                'active' => 'national_admin.programs*',
            ],
            [
                'label' => 'Official Data Sources',
                'route' => 'national_admin.sources',
                'icon' => 'globe',
                'active' => 'national_admin.sources*',
            ],
            [
                'label' => 'Web Scraping Center',
                'route' => 'national_admin.scraping',
                'icon' => 'activity',
                'badge' => '4 Active',
                'badge_color' => 'brand',
                'active' => 'national_admin.scraping*',
            ],
            [
                'label' => 'AI Extraction Review',
                'route' => 'national_admin.ai_extraction',
                'icon' => 'sparkles',
                'badge' => '4 Staged',
                'badge_color' => 'warning',
                'active' => 'national_admin.ai_extraction*',
            ],
            [
                'label' => 'Data Verification',
                'route' => 'national_admin.verification',
                'icon' => 'shield-check',
                'active' => 'national_admin.verification*',
            ],
            [
                'label' => 'Knowledge Base (RAG)',
                'route' => 'national_admin.knowledge_base',
                'icon' => 'database',
                'active' => 'national_admin.knowledge_base*',
            ],
        ],
        'secondary' => [
            [
                'label' => 'AI Chatbot Config',
                'route' => 'national_admin.chatbot_config',
                'icon' => 'bot',
                'active' => 'national_admin.chatbot_config*',
            ],
            [
                'label' => 'User Management',
                'route' => 'national_admin.users',
                'icon' => 'users',
                'active' => 'national_admin.users*',
            ],
            [
                'label' => 'Regulatory Broadcasts',
                'route' => 'national_admin.notifications',
                'icon' => 'bell',
                'active' => 'national_admin.notifications*',
            ],
            [
                'label' => 'National Analytics',
                'route' => 'national_admin.analytics',
                'icon' => 'bar-chart-3',
                'active' => 'national_admin.analytics*',
            ],
            [
                'label' => 'Settings & Audit Logs',
                'route' => 'national_admin.settings',
                'icon' => 'sliders-horizontal',
                'active' => 'national_admin.settings*',
            ],
        ],
    ],
];
