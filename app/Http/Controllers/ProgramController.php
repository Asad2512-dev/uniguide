<?php

namespace App\Http\Controllers;

use App\Services\DiscoveryService;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function __construct(
        protected DiscoveryService $discoveryService
    ) {}

    /**
     * Display the degree program catalog & explorer.
     */
    public function index(Request $request)
    {
        $filters = [
            'q' => $request->query('q', ''),
            'discipline' => $request->query('discipline', 'all'),
            'degree_level' => $request->query('degree_level', 'all'),
            'university' => $request->query('university', 'all'),
            'max_fee' => $request->query('max_fee', null),
        ];

        $sort = $request->query('sort', 'merit_desc');
        $page = max(1, (int) $request->query('page', 1));
        $perPage = 6;

        $results = $this->discoveryService->getPrograms($filters, $sort, $perPage, $page);
        $filterOptions = $this->discoveryService->getFilterOptions();

        // Applied filters for chips
        $appliedFilters = [];
        if (! empty($filters['q'])) {
            $appliedFilters['q'] = ['label' => 'Keyword: "'.e($filters['q']).'"', 'param' => 'q'];
        }
        if ($filters['discipline'] !== 'all' && isset($filterOptions['disciplines'][$filters['discipline']])) {
            $appliedFilters['discipline'] = ['label' => $filterOptions['disciplines'][$filters['discipline']], 'param' => 'discipline'];
        }
        if ($filters['degree_level'] !== 'all' && isset($filterOptions['degree_levels'][$filters['degree_level']])) {
            $appliedFilters['degree_level'] = ['label' => $filterOptions['degree_levels'][$filters['degree_level']], 'param' => 'degree_level'];
        }
        if ($filters['university'] !== 'all') {
            $uni = $this->discoveryService->getUniversityBySlug($filters['university']);
            if ($uni) {
                $appliedFilters['university'] = ['label' => 'University: '.$uni['acronym'], 'param' => 'university'];
            }
        }

        // Get university list for dropdown
        $allUniversities = $this->discoveryService->getUniversities([], 'rank', 100, 1)['data'];

        return view('pages.discovery.programs.index', [
            'results' => $results,
            'filters' => $filters,
            'filterOptions' => $filterOptions,
            'appliedFilters' => $appliedFilters,
            'universities' => $allUniversities,
            'sort' => $sort,
        ]);
    }

    /**
     * Display detailed program view with curriculum and admissions criteria.
     */
    public function show(string $slug)
    {
        $program = $this->discoveryService->getProgramBySlug($slug);

        if (! $program) {
            abort(404, 'Academic program not found');
        }

        return view('pages.discovery.programs.show', [
            'program' => $program,
        ]);
    }
}
