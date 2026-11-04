<?php

namespace App\Http\Controllers;

use App\Services\DiscoveryService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(
        protected DiscoveryService $discoveryService
    ) {}

    /**
     * Unified National Search Results view across universities & programs.
     */
    public function index(Request $request)
    {
        $query = trim($request->query('q', ''));
        $type = $request->query('type', 'all'); // 'all', 'universities', 'programs'

        $results = $this->discoveryService->search($query, $type);

        return view('pages.discovery.search', [
            'query' => $query,
            'type' => $type,
            'results' => $results,
        ]);
    }

    /**
     * Live search suggestions API endpoint (JSON).
     */
    public function suggestions(Request $request)
    {
        $query = trim($request->query('q', ''));
        if (strlen($query) < 2) {
            return response()->json(['universities' => [], 'programs' => []]);
        }

        $results = $this->discoveryService->search($query, 'all');

        $universities = collect($results['universities'])->take(5)->map(fn($u) => [
            'title' => $u['name'] . ' (' . $u['acronym'] . ')',
            'subtitle' => $u['city'] . ' • ' . $u['hec_ranking'],
            'url' => route('universities.show', $u['slug']),
            'type' => 'University',
        ]);

        $programs = collect($results['programs'])->take(5)->map(fn($p) => [
            'title' => $p['title'],
            'subtitle' => $p['university_name'] . ' • ' . $p['fee_semester'],
            'url' => route('programs.show', $p['slug']),
            'type' => 'Program',
        ]);

        return response()->json([
            'universities' => $universities,
            'programs' => $programs,
        ]);
    }
}
