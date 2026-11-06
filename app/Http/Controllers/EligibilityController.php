<?php

namespace App\Http\Controllers;

use App\Services\EligibilityRulesEngine;
use App\Services\RegionCatalog;
use App\Services\StudentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EligibilityController extends Controller
{
    public function __construct(
        protected EligibilityRulesEngine $rulesEngine,
        protected StudentService $studentService
    ) {}

    /**
     * Screen 3: Academic Eligibility Checker Wizard.
     */
    public function wizard(): View
    {
        return view('pages.eligibility.wizard', [
            'qualifications' => [
                ['id' => 'fsc', 'name' => 'FSc (Pre-Engineering / Pre-Medical)', 'desc' => 'BISE & Federal Board (FBISE)', 'icon' => 'cpu'],
                ['id' => 'ics', 'name' => 'ICS (Computer Science)', 'desc' => 'Intermediate in Computing', 'icon' => 'laptop'],
                ['id' => 'a_levels', 'name' => 'Cambridge A-Levels', 'desc' => 'Requires IBCC Equivalence Certificate', 'icon' => 'globe'],
                ['id' => 'dae', 'name' => 'DAE (Diploma of Associate Engineering)', 'desc' => '3-Year Technical Board Diploma', 'icon' => 'wrench'],
            ],
            'groups' => [
                ['id' => 'pre_engineering', 'name' => 'Pre-Engineering', 'desc' => 'Physics, Mathematics, Chemistry'],
                ['id' => 'ics', 'name' => 'ICS (Computer)', 'desc' => 'Physics, Mathematics, Computer Science'],
                ['id' => 'pre_medical', 'name' => 'Pre-Medical', 'desc' => 'Biology, Chemistry, Physics (Eligible for CS with remedial math)'],
                ['id' => 'general_science', 'name' => 'General Science', 'desc' => 'Statistics, Mathematics, Economics'],
            ],
            'programs' => [
                'Computer Science & AI', 'Software Engineering', 'Electrical Engineering',
                'Mechanical Engineering', 'Business Administration (BBA)', 'Medical & Allied Health (MBBS/DPT)',
            ],
            'locations' => app(RegionCatalog::class)->regions(),
            'entryTests' => [
                ['id' => 'net', 'name' => 'NUST Entry Test (NET)', 'max' => 200, 'typical' => 145],
                ['id' => 'fast', 'name' => 'FAST-NU Computing Test', 'max' => 100, 'typical' => 70],
                ['id' => 'nat', 'name' => 'NTS National Aptitude Test (NAT)', 'max' => 100, 'typical' => 75],
                ['id' => 'ecat', 'name' => 'Punjab ECAT (UET Lahore)', 'max' => 400, 'typical' => 240],
                ['id' => 'none', 'name' => 'Not Taken Yet / Exemption Target', 'max' => 100, 'typical' => 70],
            ],
        ]);
    }

    /**
     * Screen 4: Verified Eligibility Results.
     */
    public function results(Request $request): View
    {
        $candidate = [
            'qualification' => $request->input('qualification', 'fsc'),
            'academic_group' => $request->input('academic_group', 'pre_engineering'),
            'matric_percentage' => floatval($request->input('matric_percentage', 85.0)),
            'fsc_percentage' => floatval($request->input('fsc_percentage', 78.0)),
            'desired_program' => $request->input('desired_program', 'computing'),
            'preferred_location' => $request->input('preferred_location', 'all'),
            'test_type' => $request->input('test_type', 'net'),
            'test_score' => floatval($request->input('test_score', 145)),
        ];

        $evaluation = $this->rulesEngine->evaluateEligibility($candidate);

        return view('pages.eligibility.results', [
            'evaluation' => $evaluation,
            'candidate' => $evaluation['candidate'],
            'results' => $evaluation['results'],
            'counts' => $evaluation['counts'],
            'aggregates' => $evaluation['calculated_aggregates'],
            'rules' => $evaluation['rules'],
        ]);
    }

    /**
     * Screen 5: Eligibility Explanation Detail.
     */
    public function rule(?string $ruleId = null): View
    {
        $rules = $this->rulesEngine->getRulesRepository();
        $selectedRule = null;

        if ($ruleId && isset($rules[$ruleId])) {
            $selectedRule = $rules[$ruleId];
        } else {
            $selectedRule = reset($rules);
        }

        return view('pages.eligibility.rule', [
            'rules' => $rules,
            'selectedRule' => $selectedRule,
        ]);
    }

    /**
     * Screen 6: Personalized University Shortlist.
     */
    public function shortlist(): View
    {
        $savedUnis = $this->studentService->getSavedUniversities();
        $profile = $this->studentService->getProfile();

        return view('pages.eligibility.shortlist', [
            'universities' => $savedUnis,
            'profile' => $profile,
        ]);
    }

    /**
     * Quick save to shortlist from Eligibility / Comparison.
     */
    public function addToShortlist(Request $request): RedirectResponse
    {
        $request->validate([
            'slug' => 'required|string',
            'name' => 'required|string',
        ]);

        $saved = $this->studentService->getSavedUniversities();
        $exists = false;
        foreach ($saved as $s) {
            if ($s['slug'] === $request->input('slug')) {
                $exists = true;
                break;
            }
        }

        if (! $exists) {
            $saved[] = [
                'slug' => $request->input('slug'),
                'name' => $request->input('name'),
                'acronym' => $request->input('acronym', 'UNI'),
                'city' => $request->input('city', 'Islamabad'),
                'province_label' => 'Pakistan',
                'sector' => 'chartered',
                'hec_ranking' => 'Verified Institution',
                'fee_range' => $request->input('fee', 'PKR 180,000 / sem'),
                'deadline' => '15 July 2026',
                'match_tier' => 'Eligible Target',
                'saved_at' => now()->format('Y-m-d'),
            ];
            session()->put('uniguide_student_saved_unis', $saved);
        }

        return back()->with('toast_success', 'University added to your personalized shortlist!');
    }
}
