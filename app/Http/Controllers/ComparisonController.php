<?php

namespace App\Http\Controllers;

use App\Services\ComparisonService;
use App\Services\DiscoveryService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ComparisonController extends Controller
{
    public function __construct(
        protected ComparisonService $comparisonService,
        protected DiscoveryService $discoveryService
    ) {}

    /**
     * Screen 1: University Comparison Builder.
     */
    public function builder(Request $request): View
    {
        $presets = $this->comparisonService->getPresetComparisons();
        $availableUnis = $this->comparisonService->getSelectableUniversities();

        return view('pages.compare.builder', [
            'presets' => $presets,
            'availableUniversities' => $availableUnis,
            'selectedSlugs' => ['nust', 'fast-nuces'],
        ]);
    }

    /**
     * Screen 2: Side-by-Side Comparison Results Workspace.
     */
    public function results(Request $request): View
    {
        $unisParam = $request->input('unis', 'nust,fast-nuces');
        $slugs = is_array($unisParam) ? $unisParam : explode(',', $unisParam);
        $slugs = array_filter(array_map('trim', $slugs));

        if (empty($slugs)) {
            $slugs = ['nust', 'fast-nuces'];
        }

        $comparison = $this->comparisonService->compare($slugs);

        return view('pages.compare.results', [
            'universities' => $comparison['universities'],
            'sections' => $comparison['sections'],
            'slugs' => $comparison['slugs'],
            'count' => $comparison['count'],
            'availableUniversities' => $comparison['available_universities'],
            'presets' => $comparison['presets'],
        ]);
    }
}
