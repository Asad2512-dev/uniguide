<?php

namespace App\Services;

use App\Models\Campus;
use App\Models\Program;
use App\Models\University;

class DiscoveryService
{
    use PaginatesCatalog;

    public function getUniversities(array $filters = [], string $sort = 'rank', int $perPage = 6, int $page = 1): array
    {
        $query = University::query()->with(['campuses' => fn ($q) => $q->withCount('programs')])->withCount('programs');
        if (! empty($filters['q'])) {
            $query->search(trim($filters['q']));
        }
        if (! empty($filters['province']) && $filters['province'] !== 'all') {
            $catalog = app(RegionCatalog::class);
            if (! $catalog->allowsRegion($filters['province'])) {
                $query->whereRaw('1 = 0');
            } else {
                $slugs = $catalog->provinceSlugs($filters['province']);
                $query->where(fn ($q) => $q->whereHas('location.province', fn ($p) => $p->whereIn('slug', $slugs))->orWhereHas('campuses.location.province', fn ($p) => $p->whereIn('slug', $slugs)));
            }
        }
        if (! empty($filters['city']) && $filters['city'] !== 'all') {
            $query->where(fn ($q) => $q->whereHas('location', fn ($c) => $c->whereRaw('LOWER(name) = ?', [strtolower($filters['city'])]))->orWhereHas('campuses.location', fn ($c) => $c->whereRaw('LOWER(name) = ?', [strtolower($filters['city'])])));
        }
        $query->bySector($filters['sector'] ?? null);
        foreach (['discipline' => 'discipline', 'degree_level' => 'degree_level'] as $filter => $column) {
            if (! empty($filters[$filter]) && $filters[$filter] !== 'all') {
                $query->whereHas('programs', fn ($program) => $program->where($column, $filters[$filter]));
            }
        }
        if (! empty($filters['hec_category']) && $filters['hec_category'] !== 'all') {
            $query->where('hec_category', 'like', '%'.$filters['hec_category'].'%');
        }
        if (! empty($filters['max_fee']) && is_numeric($filters['max_fee'])) {
            $query->where('fee_min', '>', 0)->where('fee_min', '<=', (int) $filters['max_fee']);
        }
        [$column, $direction] = match ($sort) {
            'name_asc' => ['name', 'asc'], 'name_desc' => ['name', 'desc'],
            'fee_asc' => ['fee_min', 'asc'], 'fee_desc' => ['fee_max', 'desc'],
            default => ['id', 'asc'],
        };
        $perPage = max(1, min($perPage, 500));
        $page = max(1, $page);
        $results = $query->orderBy($column, $direction)->orderBy('id')->paginate($perPage, ['*'], 'page', $page);

        return [
            'data' => $results->getCollection()->map(fn (University $university): array => $this->presentUniversity($university))->all(),
            'total' => $results->total(), 'per_page' => $perPage, 'current_page' => $results->currentPage(),
            'total_pages' => $results->lastPage(), 'from' => $results->firstItem() ?? 0, 'to' => $results->lastItem() ?? 0,
        ];
    }

    /**
     * Get a single university by slug.
     */
    public function getUniversityBySlug(string $slug): ?array
    {
        $university = University::with(['campuses.programs', 'sourceRecords.dataSource'])->withCount('programs')->where('slug', $slug)->first();

        return $university ? $this->presentUniversity($university, true) : null;
    }

    /** @return array<string, mixed> */
    private function presentUniversity(University $university, bool $detail = false): array
    {
        $legacy = [];
        $data = array_replace($legacy, $university->toArray());
        foreach (['acronym', 'hec_ranking', 'hec_category', 'fee_range', 'acceptance_rate', 'total_students', 'faculty_phd_ratio', 'campus_size', 'hostel_availability', 'charter_citation', 'gazette_reference', 'deadline', 'description', 'address', 'phone', 'email', 'charter_type', 'established'] as $field) {
            $data[$field] = $data[$field] ?? 'Not verified';
        }
        $data['acronym'] = $university->acronym ?? '—';
        $data['website'] = $university->website ?? '';
        $data['accreditations'] = $data['accreditations'] ?? [];
        $data['scholarships_offered'] = [];
        $data['related_universities'] = [];
        $data['programs'] = $detail ? $university->programs()->with(['university', 'campus'])->get()->map(fn (Program $p): array => $this->presentProgram($p))->all() : [];
        $data['campuses'] = $university->campuses->map(function ($campus) use ($legacy): array {
            $previous = collect($legacy['campuses'] ?? [])->firstWhere('slug', $campus->slug) ?? [];
            $item = array_replace($previous, $campus->toArray());
            $item['is_main'] = $campus->is_main_campus;
            $item['programs_offered_count'] = $campus->programs_count ?? $campus->programs->count();
            foreach (['address', 'phone', 'email', 'hostel_capacity', 'transport', 'libraries'] as $field) {
                $item[$field] = $item[$field] ?? 'Not verified';
            }
            $item['facilities'] = $item['facilities'] ?? [];

            return $item;
        })->all();

        return $data;
    }

    /**
     * Get campus by university slug and campus slug.
     */
    public function getCampus(string $uniSlug, string $campusSlug): ?array
    {
        $uni = $this->getUniversityBySlug($uniSlug);
        if (! $uni) {
            return null;
        }

        $campus = collect($uni['campuses'] ?? [])->firstWhere('slug', $campusSlug);
        if (! $campus) {
            return null;
        }

        $campus['university'] = $uni;
        $campus['programs'] = Program::with(['university', 'campus'])->where('campus_id', $campus['id'])->get()->map(fn (Program $program): array => $this->presentProgram($program))->all();

        return $campus;
    }

    /**
     * Get filtered programs with pagination.
     */
    public function getPrograms(array $filters = [], string $sort = 'merit_desc', int $perPage = 6, int $page = 1): array
    {
        $query = Program::with(['university', 'campus']);
        if (! empty($filters['q'])) {
            $term = '%'.trim($filters['q']).'%';
            $query->where(fn ($q) => $q->where('name', 'like', $term)->orWhere('discipline', 'like', $term)->orWhereHas('university', fn ($u) => $u->where('name', 'like', $term)));
        }
        foreach (['discipline', 'degree_level'] as $column) {
            if (! empty($filters[$column]) && $filters[$column] !== 'all') {
                $query->where($column, $filters[$column]);
            }
        }
        if (! empty($filters['university']) && $filters['university'] !== 'all') {
            $query->whereHas('university', fn ($q) => $q->where('slug', $filters['university']));
        }
        $integerType = $query->getConnection()->getDriverName() === 'sqlite' ? 'INTEGER' : 'UNSIGNED';
        $fee = "CAST(REPLACE(REPLACE(REPLACE(COALESCE(fee_per_semester, '0'), 'PKR', ''), ',', ''), ' ', '') AS {$integerType})";
        if (! empty($filters['max_fee']) && is_numeric($filters['max_fee'])) {
            $query->whereRaw($fee.' <= ?', [(int) $filters['max_fee']]);
        }
        match ($sort) {
            'title_asc' => $query->orderBy('name'),
            'fee_asc' => $query->orderByRaw($fee.' ASC'),
            'fee_desc' => $query->orderByRaw($fee.' DESC'),
            default => $query->orderByRaw('CAST(COALESCE(cutoff_merit, 0) AS DECIMAL(8,2)) DESC'),
        };

        return $this->catalogPage($query->orderBy('id'), $perPage, $page, fn (Program $p) => $this->presentProgram($p));
    }

    /**
     * Get single program by slug.
     */
    public function getProgramBySlug(string $slug): ?array
    {
        $program = Program::with(['university', 'campus'])->where('slug', $slug)->first();
        if (! $program) {
            return null;
        }
        $data = $this->presentProgram($program);
        $data['university'] = $this->getUniversityBySlug($program->university->slug);
        $data['resolved_related'] = [];

        return $data;
    }

    private function presentProgram(Program $program): array
    {
        $legacy = [];
        $data = array_replace($legacy, $program->toArray());

        return array_replace([
            'department' => 'Not verified', 'degree_level_label' => $program->degree_level ?? '',
            'discipline_label' => $program->discipline ?? '', 'duration' => $program->duration_years.' years',
            'study_mode' => 'Not verified', 'shift' => 'Not verified', 'accreditation' => 'Not verified',
            'council' => 'Not verified', 'deadline' => 'Not verified', 'admission_criteria' => [],
            'curriculum_highlights' => [], 'career_outlook' => 'Not verified', 'related_programs' => [],
        ], $data, [
            'estimated_closing_merit' => $program->estimated_closing_merit ?? 'Not recorded',
            'fee_per_semester' => $program->fee_per_semester ?? 'Not recorded',
            'title' => $program->name, 'university_slug' => $program->university->slug,
            'university_name' => $program->university->name,
            'available_campuses' => [$program->campus->slug], 'campus_id' => $program->campus_id,
            'city' => $program->campus->city, 'province' => app(RegionCatalog::class)->normalizeRegion($program->campus->province),
            'fee_raw' => (int) preg_replace('/[^0-9]/', '', $program->fee_per_semester ?? ''),
            'merit_raw' => (float) ($program->cutoff_merit ?? 0),
            'cutoff_merit' => $program->cutoff_merit ?? 'Not verified',
            'fee_semester' => $program->fee_per_semester ?? 'Not verified',
            'overview' => $program->description ?? 'Program details are pending verification.',
        ]);
    }

    /**
     * Search across universities, programs, and scholarships.
     */
    public function search(string $query, string $type = 'all'): array
    {
        $q = strtolower(trim($query));
        $results = [
            'universities' => [],
            'programs' => [],
            'scholarships' => [],
            'total' => 0,
        ];

        if (empty($q)) {
            return $results;
        }

        if ($type === 'all' || $type === 'universities') {
            $results['universities'] = $this->getUniversities(['q' => $query], 'name_asc', 50)['data'];
        }

        if ($type === 'all' || $type === 'programs') {
            $results['programs'] = $this->getPrograms(['q' => $query], 'title_asc', 100)['data'];
        }

        $results['total'] = count($results['universities']) + count($results['programs']);

        return $results;
    }

    /**
     * Get filter options for dropdowns.
     */
    public function getFilterOptions(): array
    {
        return [
            'provinces' => ['all' => 'All covered regions'] + app(RegionCatalog::class)->regions(),
            'cities' => ['all' => 'All Cities'] + app(RegionCatalog::class)->cities(),
            'sectors' => [
                'all' => 'All Sectors (Public & Private)',
                'public' => 'Public Sector (Government)',
                'private' => 'Chartered Private Sector',
            ],
            'disciplines' => [
                'all' => 'All Academic Disciplines',
                'computing' => 'Computing & Artificial Intelligence',
                'engineering' => 'Engineering & Technology (PEC)',
                'business' => 'Business & Management (NBEAC)',
                'medical' => 'Medical & Health Sciences (PMDC)',
                'natural_sciences' => 'Natural & Applied Sciences',
                'social_sciences' => 'Social Sciences & Humanities',
            ],
            'degree_levels' => [
                'all' => 'All Degree Levels',
                'bs' => 'Undergraduate (BS / BE / BBA)',
                'ms' => 'Postgraduate (MS / MPhil / MBA)',
                'phd' => 'Doctoral (PhD)',
            ],
            'hec_categories' => [
                'all' => 'All HEC Categories',
                'category w' => 'Category W (Highest Quality Tier)',
                'category x' => 'Category X',
            ],
        ];
    }
}
