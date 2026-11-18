<?php

namespace App\Http\Controllers;

use App\Services\AdmissionService;
use App\Services\DiscoveryService;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the UniGuide Pakistan Homepage.
     */
    public function index(): View
    {
        $comparisonMatrix = ['institutions' => [], 'attributes' => []];

        $discovery = app(DiscoveryService::class);
        $featuredUniversities = array_map(fn ($u) => array_replace($u, [
            'location' => $u['city'], 'hecRank' => '', 'feeRange' => $u['fee_range'],
            'programsCount' => $u['programs_count'].' recorded offerings', 'acceptanceRate' => $u['acceptance_rate'],
            'category' => 'University', 'province' => $u['province_label'],
        ]), $discovery->getUniversities([], 'name_asc', 6)['data']);
        $popularPrograms = array_map(fn ($p) => ['slug' => $p['slug'], 'title' => $p['title'], 'university' => $p['university_name'], 'degreeLevel' => $p['degree_level_label'], 'duration' => $p['duration'], 'feePerSemester' => $p['fee_semester'], 'cutoffMerit' => 'Not verified', 'accreditation' => 'Not verified', 'status' => $p['status']], $discovery->getPrograms([], 'title_asc', 4)['data']);
        $scholarships = app(AdmissionService::class)->getScholarships([], 'title_asc', 4)['data'];

        return view('pages.marketing.home', [
            'featuredUniversities' => $featuredUniversities,
            'popularPrograms' => $popularPrograms,
            'scholarships' => $scholarships,
            'comparisonMatrix' => $comparisonMatrix,
            'searchSuggestions' => collect($featuredUniversities)->map(fn ($u) => ['type' => 'University', 'title' => $u['name'], 'meta' => $u['city'], 'route' => route('universities.show', $u['slug'])])->all(),
        ]);
    }
}
