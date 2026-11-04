<?php

namespace App\Http\Controllers;

use App\Services\DiscoveryService;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
    public function __construct(
        protected DiscoveryService $discoveryService
    ) {}

    /**
     * Display the National University Directory.
     */
    public function index(Request $request)
    {
        $filters = [
            'q' => $request->query('q', ''),
            'province' => $request->query('province', 'all'),
            'city' => $request->query('city', 'all'),
            'sector' => $request->query('sector', 'all'),
            'discipline' => $request->query('discipline', 'all'),
            'degree_level' => $request->query('degree_level', 'all'),
            'hec_category' => $request->query('hec_category', 'all'),
            'max_fee' => $request->query('max_fee', null),
        ];

        $sort = $request->query('sort', 'rank');
        $view = $request->query('view', 'grid'); // 'grid' or 'list'
        $page = max(1, (int)$request->query('page', 1));
        $perPage = 6;

        $results = $this->discoveryService->getUniversities($filters, $sort, $perPage, $page);
        $filterOptions = $this->discoveryService->getFilterOptions();

        // Calculate active applied filters
        $appliedFilters = [];
        if (!empty($filters['q'])) {
            $appliedFilters['q'] = ['label' => 'Query: "' . e($filters['q']) . '"', 'param' => 'q'];
        }
        if ($filters['province'] !== 'all' && isset($filterOptions['provinces'][$filters['province']])) {
            $appliedFilters['province'] = ['label' => $filterOptions['provinces'][$filters['province']], 'param' => 'province'];
        }
        if ($filters['city'] !== 'all' && isset($filterOptions['cities'][$filters['city']])) {
            $appliedFilters['city'] = ['label' => 'City: ' . $filterOptions['cities'][$filters['city']], 'param' => 'city'];
        }
        if ($filters['sector'] !== 'all' && isset($filterOptions['sectors'][$filters['sector']])) {
            $appliedFilters['sector'] = ['label' => $filterOptions['sectors'][$filters['sector']], 'param' => 'sector'];
        }
        if ($filters['discipline'] !== 'all' && isset($filterOptions['disciplines'][$filters['discipline']])) {
            $appliedFilters['discipline'] = ['label' => $filterOptions['disciplines'][$filters['discipline']], 'param' => 'discipline'];
        }
        if ($filters['degree_level'] !== 'all' && isset($filterOptions['degree_levels'][$filters['degree_level']])) {
            $appliedFilters['degree_level'] = ['label' => $filterOptions['degree_levels'][$filters['degree_level']], 'param' => 'degree_level'];
        }
        if ($filters['hec_category'] !== 'all' && isset($filterOptions['hec_categories'][$filters['hec_category']])) {
            $appliedFilters['hec_category'] = ['label' => $filterOptions['hec_categories'][$filters['hec_category']], 'param' => 'hec_category'];
        }

        return view('pages.discovery.universities.index', [
            'results' => $results,
            'filters' => $filters,
            'filterOptions' => $filterOptions,
            'appliedFilters' => $appliedFilters,
            'sort' => $sort,
            'view' => $view,
        ]);
    }

    /**
     * Display a specific university profile.
     */
    public function show(string $slug)
    {
        $university = $this->discoveryService->getUniversityBySlug($slug);

        if (!$university) {
            abort(404, 'University not found in national registry');
        }

        // Get programs for this university
        $programsResult = $this->discoveryService->getPrograms(['university' => $slug], 'merit_desc', 50, 1);
        $programs = $programsResult['data'];

        // Get related universities (same province or sector, excluding this one)
        $relatedResult = $this->discoveryService->getUniversities([
            'sector' => $university['sector'],
        ], 'rank', 3, 1);
        
        $relatedUniversities = collect($relatedResult['data'])
            ->filter(fn($u) => $u['slug'] !== $slug)
            ->take(3)
            ->values()
            ->all();

        return view('pages.discovery.universities.show', [
            'university' => $university,
            'programs' => $programs,
            'relatedUniversities' => $relatedUniversities,
        ]);
    }

    /**
     * Display a specific campus detail.
     */
    public function campus(string $slug, string $campusSlug)
    {
        $campus = $this->discoveryService->getCampus($slug, $campusSlug);

        if (!$campus) {
            abort(404, 'Campus not found for this institution');
        }

        $university = $campus['university'];

        return view('pages.discovery.universities.campus', [
            'university' => $university,
            'campus' => $campus,
        ]);
    }
}

