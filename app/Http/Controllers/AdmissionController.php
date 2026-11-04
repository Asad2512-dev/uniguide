<?php

namespace App\Http\Controllers;

use App\Services\AdmissionService;
use App\Services\DiscoveryService;
use Illuminate\Http\Request;

class AdmissionController extends Controller
{
    public function __construct(
        protected AdmissionService $admissionService,
        protected DiscoveryService $discoveryService
    ) {}

    /**
     * Display the Admission Explorer listing.
     */
    public function index(Request $request)
    {
        $filters = [
            'q' => $request->query('q', ''),
            'status' => $request->query('status', 'all'),
            'province' => $request->query('province', 'all'),
            'degree_level' => $request->query('degree_level', 'all'),
            'session' => $request->query('session', 'all'),
            'university' => $request->query('university', 'all'),
        ];

        $sort = $request->query('sort', 'deadline_asc');
        $page = max(1, (int) $request->query('page', 1));
        $perPage = 6;

        $results = $this->admissionService->getAdmissions($filters, $sort, $perPage, $page);
        $filterOptions = $this->admissionService->getFilterOptions();

        // Calculate applied filters for chips
        $appliedFilters = [];
        if (! empty($filters['q'])) {
            $appliedFilters['q'] = ['label' => 'Query: "'.e($filters['q']).'"', 'param' => 'q'];
        }
        if ($filters['status'] !== 'all' && isset($filterOptions['statuses'][$filters['status']])) {
            $appliedFilters['status'] = ['label' => $filterOptions['statuses'][$filters['status']], 'param' => 'status'];
        }
        if ($filters['province'] !== 'all' && isset($filterOptions['provinces'][$filters['province']])) {
            $appliedFilters['province'] = ['label' => $filterOptions['provinces'][$filters['province']], 'param' => 'province'];
        }
        if ($filters['degree_level'] !== 'all' && isset($filterOptions['degree_levels'][$filters['degree_level']])) {
            $appliedFilters['degree_level'] = ['label' => $filterOptions['degree_levels'][$filters['degree_level']], 'param' => 'degree_level'];
        }
        if ($filters['session'] !== 'all' && isset($filterOptions['sessions'][$filters['session']])) {
            $appliedFilters['session'] = ['label' => $filterOptions['sessions'][$filters['session']], 'param' => 'session'];
        }

        $allUniversities = $this->discoveryService->getUniversities([], 'rank', 100, 1)['data'];

        return view('pages.admissions.index', [
            'results' => $results,
            'filters' => $filters,
            'filterOptions' => $filterOptions,
            'appliedFilters' => $appliedFilters,
            'universities' => $allUniversities,
            'sort' => $sort,
        ]);
    }

    /**
     * Display detailed admission view.
     */
    public function show(string $slug)
    {
        $admission = $this->admissionService->getAdmissionBySlug($slug);

        if (! $admission) {
            abort(404, 'Admission campaign not found');
        }

        $university = $this->discoveryService->getUniversityBySlug($admission['university_slug']);

        return view('pages.admissions.show', [
            'admission' => $admission,
            'university' => $university,
        ]);
    }

    /**
     * Display the Admission Deadline Calendar.
     */
    public function calendar(Request $request)
    {
        $month = $request->query('month') !== null ? (int) $request->query('month') : null;
        $year = (int) $request->query('year', 2026);
        $filterCategory = $request->query('category', 'all'); // 'all', 'deadline', 'test', 'scholarship', 'merit'

        $allEvents = $this->admissionService->getCalendarEvents($year, $month);

        if ($filterCategory !== 'all') {
            $events = collect($allEvents)->where('type', $filterCategory)->values()->all();
        } else {
            $events = $allEvents;
        }

        return view('pages.admissions.calendar', [
            'events' => $events,
            'selectedMonth' => $month,
            'selectedYear' => $year,
            'selectedCategory' => $filterCategory,
            'totalEventsCount' => count($allEvents),
        ]);
    }

    /**
     * Display the Admission Document Checklist.
     */
    public function checklist()
    {
        $checklist = $this->admissionService->getDocumentChecklist();

        return view('pages.admissions.checklist', [
            'checklist' => $checklist,
        ]);
    }
}
