<?php

namespace App\Http\Controllers;

use App\Services\AdmissionService;
use Illuminate\Http\Request;

class ScholarshipController extends Controller
{
    public function __construct(
        protected AdmissionService $admissionService
    ) {}

    /**
     * Display the national scholarship directory.
     */
    public function index(Request $request)
    {
        $filters = [
            'q' => $request->query('q', ''),
            'type' => $request->query('type', 'all'),
            'status' => $request->query('status', 'all'),
        ];

        $sort = $request->query('sort', 'deadline_asc');
        $page = max(1, (int)$request->query('page', 1));
        $perPage = 6;

        $results = $this->admissionService->getScholarships($filters, $sort, $perPage, $page);
        $filterOptions = $this->admissionService->getFilterOptions();

        $appliedFilters = [];
        if (!empty($filters['q'])) {
            $appliedFilters['q'] = ['label' => 'Query: "' . e($filters['q']) . '"', 'param' => 'q'];
        }
        if ($filters['type'] !== 'all' && isset($filterOptions['scholarship_types'][$filters['type']])) {
            $appliedFilters['type'] = ['label' => $filterOptions['scholarship_types'][$filters['type']], 'param' => 'type'];
        }
        if ($filters['status'] !== 'all' && isset($filterOptions['statuses'][$filters['status']])) {
            $appliedFilters['status'] = ['label' => $filterOptions['statuses'][$filters['status']], 'param' => 'status'];
        }

        return view('pages.scholarships.index', [
            'results' => $results,
            'filters' => $filters,
            'filterOptions' => $filterOptions,
            'appliedFilters' => $appliedFilters,
            'sort' => $sort,
        ]);
    }

    /**
     * Display a specific scholarship details page.
     */
    public function show(string $slug)
    {
        $scholarship = $this->admissionService->getScholarshipBySlug($slug);

        if (!$scholarship) {
            abort(404, 'Scholarship program not found');
        }

        return view('pages.scholarships.show', [
            'scholarship' => $scholarship,
        ]);
    }
}
