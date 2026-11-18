<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\SystemSetting;
use App\Models\University;
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
        return view('pages.marketing.about', ['leadership' => SystemSetting::get('content.leadership', []), 'charterMilestones' => SystemSetting::get('content.milestones', [])]);
    }

    /**
     * Display the How It Works screen.
     */
    public function howItWorks(): View
    {
        return view('pages.marketing.how-it-works', ['workflowSteps' => [
            ['step' => '01', 'title' => 'Explore the university directory', 'subtitle' => 'Recorded university information', 'description' => 'Find institutions and campuses using the records available on UniGuide.', 'features' => ['Search by name and city', 'Review recorded institution details'], 'icon' => 'building-2'],
            ['step' => '02', 'title' => 'Build your academic profile', 'subtitle' => 'Your own academic records', 'description' => 'Save your marks, stream, entry test results, and private supporting documents.', 'features' => ['Update results as they become available', 'Keep private documents with your account'], 'icon' => 'user'],
            ['step' => '03', 'title' => 'Research your options', 'subtitle' => 'Source-backed guidance', 'description' => 'Use the counselor to explore database records. Missing requirements and fees are not estimated.', 'features' => ['Save your shortlist', 'Keep your conversations'], 'icon' => 'message-circle'],
            ['step' => '04', 'title' => 'Track your applications', 'subtitle' => 'Plan your next steps', 'description' => 'Track your own application progress. Complete applications and payments through official institution channels.', 'features' => ['Manage application stages', 'Save in-app deadline reminders'], 'icon' => 'calendar'],
        ]]);
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
                'description' => 'Find institutions by exact criteria: public vs private sector, PEC accreditation tier, maximum semester tuition, hostel availability, and institution information.',
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
                'title' => 'Scholarship & Endowment Matcher',
                'description' => 'Instantly match your family income, domicile, and academic merit with over PKR 4.2B in available grants.',
                'icon' => 'award',
                'highlights' => ['Ehsaas Undergraduate Phase-IV criteria engine', 'Need-Based Grants for public sector', 'Provincial PEEF and USAID funding guidelines'],
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
                'highlights' => ['Bank-grade encryption for identity docs', 'Instant reuse across multiple university applications', 'Prevents duplicate notarization expenses'],
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
        $regionalDesks = SystemSetting::get('content.regional_desks', []);

        return view('pages.marketing.contact', [
            'regionalDesks' => $regionalDesks,
        ]);
    }

    /**
     * Process contact and support inquiry submission.
     */
    public function submitContact(Request $request): RedirectResponse
    {
        $data = $request->validate(['name' => 'required|string|max:150', 'email' => 'required|email|max:255', 'message' => 'required|string|max:5000', 'subject' => 'nullable|string|max:200', 'university_slug' => 'nullable|string|max:255']);
        $university = ! empty($data['university_slug']) ? University::where('slug', $data['university_slug'])->firstOrFail() : null;
        unset($data['university_slug']);
        Inquiry::create($data + ['user_id' => $request->user()?->id, 'university_id' => $university?->id, 'category' => $university ? 'admissions' : 'platform', 'institution_slug' => $university?->slug ?? 'platform', 'status' => 'new', 'subject' => $data['subject'] ?? 'Platform contact']);

        return back()->with('toast_success', 'Your inquiry has been saved for review.');
    }
}
