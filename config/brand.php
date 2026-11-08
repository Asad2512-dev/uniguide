<?php

return [
    /*
    |--------------------------------------------------------------------------
    | UniGuide Pakistan — Centralized Brand Configuration
    |--------------------------------------------------------------------------
    |
    | Single source of truth for identity, naming, metadata, contact channels,
    | brand assets, and legal notices across the platform. Non-programmers
    | can edit this file to update branding across all public and portal shells.
    |
    */

    'name' => 'UniGuide Pakistan',
    'short_name' => 'UniGuide',
    'acronym' => 'UG',
    'country' => 'Pakistan',
    'country_code' => 'PK',
    'tagline' => 'National Higher Education Discovery & Admissions Gateway',
    'subtitle' => 'National Higher Education Portal',
    'jurisdiction' => 'Punjab & Islamabad Capital Territory (ICT)',

    'description' => 'UniGuide Pakistan is the national AI-powered platform for university discovery, verified admission merit calculation, degree program comparison, and scholarship tracking.',

    'seo' => [
        'default_title' => 'UniGuide Pakistan — National University Discovery & Admissions Platform',
        'title_suffix' => 'Premium Higher Education Discovery & Admissions Platform',
        'default_description' => 'Discover universities and admission opportunities across Punjab and Islamabad.',
        'keywords' => 'Pakistani universities, HEC admissions, merit calculator, NUST, FAST-NUCES, LUMS, scholarships Pakistan, PEEF, Ehsaas, entry tests',
        'theme_color' => '#4355E8',
        'og_type' => 'website',
        'og_locale' => 'en_PK',
    ],

    'contact' => [
        'email' => 'info@uniguide.pk',
        'support_email' => 'support@uniguide.pk',
        'admissions_email' => 'admissions.ict@uniguide.pk',
        'punjab_email' => 'punjab.desk@uniguide.pk',
        'phone' => '+92 (051) 9040-2026',
        'punjab_phone' => '+92 (042) 3578-2026',
        'hours' => 'Mon – Fri: 9:00 AM – 5:00 PM PKT',
        'desks' => [
            'ict' => [
                'region' => 'Federal & ICT Headquarters',
                'address' => 'Higher Education Complex, Sector H-9/1, Islamabad',
                'phone' => '+92 (051) 9040-2026',
                'email' => 'admissions.ict@uniguide.pk',
                'hours' => 'Mon – Fri: 9:00 AM – 5:00 PM PKT',
            ],
            'punjab' => [
                'region' => 'Punjab Regional Coordination',
                'address' => 'Mall Road Academic Secretariat, Lahore',
                'phone' => '+92 (042) 3578-2026',
                'email' => 'punjab.desk@uniguide.pk',
                'hours' => 'Mon – Fri: 9:00 AM – 5:00 PM PKT',
            ],
        ],
    ],

    'urls' => [
        'website' => env('APP_URL', 'https://uniguide.pk'),
        'social' => [
            'twitter' => 'https://twitter.com/UniGuidePK',
            'facebook' => 'https://facebook.com/UniGuidePakistan',
            'linkedin' => 'https://linkedin.com/company/uniguide-pakistan',
            'instagram' => 'https://instagram.com/uniguide.pk',
            'youtube' => 'https://youtube.com/@UniGuidePakistan',
        ],
    ],

    'assets' => [
        'logo' => 'images/brand/logo.svg',
        'logo_mark' => 'images/brand/logo-mark.svg',
        'wordmark' => 'images/brand/wordmark.svg',
        'favicon' => 'favicon.ico',
        'favicon_svg' => 'favicon.svg',
        'favicon_16' => 'favicon-16x16.png',
        'favicon_32' => 'favicon-32x32.png',
        'apple_touch_icon' => 'apple-touch-icon.png',
        'manifest' => 'site.webmanifest',
    ],

    'legal' => [
        'copyright' => '© :year UniGuide Pakistan. All rights reserved. Data verified against official provincial charters and gazetted fee schedules.',
        'charter_notice' => 'Official Pakistani Higher Education Verification Protocol',
        'regulatory_sync' => 'National Registry Sync',
        'verification_level' => 'HEC Chartered & Council Accredited (PEC, PMDC, NCEAC)',
    ],
];

