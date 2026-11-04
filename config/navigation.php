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
            'label' => 'Explore Universities',
            'route' => 'design-system',
            'fragment' => 'domain-cards',
            'icon' => 'building-2',
            'description' => 'Browse 240+ HEC-recognized public and private institutions across Pakistan.',
        ],
        [
            'label' => 'Degree Programs',
            'route' => 'design-system',
            'fragment' => 'domain-cards',
            'icon' => 'graduation-cap',
            'description' => 'Compare BS, MS, and PhD programs with cutoff merit criteria and fee structures.',
        ],
        [
            'label' => 'Scholarships',
            'route' => 'design-system',
            'fragment' => 'domain-cards',
            'icon' => 'award',
            'badge' => '2026-27',
            'description' => 'National Ehsaas, HEC Indigenous, USAID, and institutional merit grants.',
        ],
        [
            'label' => 'AI Advisor',
            'route' => 'design-system',
            'fragment' => 'ai-guidance',
            'icon' => 'sparkles',
            'badge' => 'Beta',
            'description' => 'Personalized merit calculator, eligibility engine, and admission counseling.',
        ],
        [
            'label' => 'Compare',
            'route' => 'design-system',
            'fragment' => 'data-tables',
            'icon' => 'scale',
            'description' => 'Side-by-side fee, ranking, location, and student satisfaction comparison.',
        ],
    ],

    'student' => [
        'main' => [
            [
                'label' => 'Dashboard Overview',
                'route' => 'preview.student',
                'icon' => 'layout-dashboard',
                'active' => 'preview.student',
            ],
            [
                'label' => 'My Applications',
                'route' => 'preview.student',
                'icon' => 'file-text',
                'badge' => '3 Active',
                'badge_color' => 'brand',
            ],
            [
                'label' => 'Saved Programs',
                'route' => 'preview.student',
                'icon' => 'bookmark',
                'badge' => '8',
            ],
            [
                'label' => 'Merit Calculator',
                'route' => 'preview.student',
                'icon' => 'calculator',
            ],
            [
                'label' => 'AI Admission Advisor',
                'route' => 'preview.student',
                'icon' => 'sparkles',
                'badge' => 'Online',
                'badge_color' => 'success',
            ],
            [
                'label' => 'Scholarship Tracker',
                'route' => 'preview.student',
                'icon' => 'award',
            ],
        ],
        'secondary' => [
            [
                'label' => 'Document Locker',
                'route' => 'preview.student',
                'icon' => 'folder-lock',
            ],
            [
                'label' => 'Eligibility Profile',
                'route' => 'preview.student',
                'icon' => 'user-check',
            ],
            [
                'label' => 'Notifications',
                'route' => 'preview.student',
                'icon' => 'bell',
                'badge' => '2',
                'badge_color' => 'warning',
            ],
            [
                'label' => 'Account Settings',
                'route' => 'preview.student',
                'icon' => 'settings',
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
