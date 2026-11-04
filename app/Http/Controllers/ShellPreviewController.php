<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ShellPreviewController extends Controller
{
    /**
     * Preview Shell 1: Public Marketing & Discovery
     */
    public function public(): View
    {
        return view('pages.preview.public', [
            'metrics' => [
                ['title' => 'Recognized Universities', 'value' => '248', 'change' => '+6', 'changeType' => 'up', 'icon' => 'building-2', 'iconColor' => 'brand', 'period' => 'HEC Recognized 2026'],
                ['title' => 'Accredited Programs', 'value' => '6,420', 'change' => '+14%', 'changeType' => 'up', 'icon' => 'graduation-cap', 'iconColor' => 'success', 'period' => 'BS, MS, and PhD'],
                ['title' => 'Active Scholarships', 'value' => 'PKR 4.2B', 'change' => '+22%', 'changeType' => 'up', 'icon' => 'award', 'iconColor' => 'lavender', 'period' => 'Ehsaas, HEC & USAID'],
                ['title' => 'Admission Inquiries', 'value' => '340K+', 'change' => '+38%', 'changeType' => 'up', 'icon' => 'users', 'iconColor' => 'warning', 'period' => 'Fall 2026 Cycle'],
            ],
        ]);
    }

    /**
     * Preview Shell 2: Authenticated Student Portal
     */
    public function student(): View
    {
        return view('pages.preview.student', [
            'applicant' => [
                'name' => 'Ahmad Raza Khan',
                'trackingNumber' => 'UG-2026-8841',
                'matricMarks' => '1042 / 1100 (94.7%)',
                'fscMarks' => '482 / 520 (92.6% Part-I)',
                'netScore' => '156 / 200 (NUST Entry Test)',
                'calculatedAggregate' => '82.45%',
            ],
            'applications' => [
                ['university' => 'NUST Islamabad', 'program' => 'BS Software Engineering', 'status' => 'Merit List Declared', 'statusVariant' => 'brand', 'meritPosition' => '#142 (Selected - 1st List)', 'deadline' => 'Fee Due 18 July'],
                ['university' => 'FAST-NUCES Islamabad', 'program' => 'BS Computer Science', 'status' => 'Application Verified', 'statusVariant' => 'success', 'meritPosition' => 'Test Scheduled: 12 July', 'deadline' => 'In Progress'],
                ['university' => 'GIKI Topi KPK', 'program' => 'BS Artificial Intelligence', 'status' => 'Under Review', 'statusVariant' => 'warning', 'meritPosition' => 'Aggregate: 81.9%', 'deadline' => 'Closing 20 July'],
            ],
        ]);
    }

    /**
     * Preview Shell 3: University Administrator Console
     */
    public function universityAdmin(): View
    {
        return view('pages.preview.university-admin', [
            'institution' => [
                'name' => 'National University of Sciences & Technology',
                'acronym' => 'NUST',
                'campuses' => 'H-12 Islamabad • Rawalpindi • Risalpur • Karachi',
                'activeCycle' => 'Fall 2026 Undergraduate Cycle',
            ],
            'stats' => [
                ['title' => 'Total Received Applications', 'value' => '42,850', 'change' => '+18.4%', 'changeType' => 'up', 'icon' => 'file-text', 'iconColor' => 'brand'],
                ['title' => 'Verified Candidates', 'value' => '38,120', 'change' => '+12.1%', 'changeType' => 'up', 'icon' => 'user-check', 'iconColor' => 'success'],
                ['title' => 'Merit Seats Allocated', 'value' => '3,450', 'change' => '100% Filled', 'changeType' => 'neutral', 'icon' => 'award', 'iconColor' => 'lavender'],
                ['title' => 'Pending Verification Flags', 'value' => '142', 'change' => '-35%', 'changeType' => 'down', 'icon' => 'alert-triangle', 'iconColor' => 'warning'],
            ],
        ]);
    }

    /**
     * Preview Shell 4: National Super Administrator Command
     */
    public function nationalAdmin(): View
    {
        return view('pages.preview.national-admin', [
            'overview' => [
                ['title' => 'Nationwide Institutions', 'value' => '248', 'change' => '142 Public • 106 Chartered', 'changeType' => 'neutral', 'icon' => 'building-2', 'iconColor' => 'brand'],
                ['title' => 'National Student Enrollment', 'value' => '1.84M', 'change' => '+6.8% YoY', 'changeType' => 'up', 'icon' => 'users', 'iconColor' => 'success'],
                ['title' => 'Accreditation Compliance', 'value' => '94.2%', 'change' => '+3.1% Audit', 'changeType' => 'up', 'icon' => 'shield-check', 'iconColor' => 'lavender'],
                ['title' => 'Regulatory Warning Notices', 'value' => '4', 'change' => 'Action Underway', 'changeType' => 'down', 'icon' => 'alert-circle', 'iconColor' => 'error'],
            ],
        ]);
    }
}
