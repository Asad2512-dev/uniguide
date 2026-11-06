<?php

namespace App\Console\Commands;

use App\Models\Campus;
use App\Models\DataSource;
use App\Models\University;
use App\Models\UniversitySourceRecord;
use App\Services\UniversityImport\VerifiedSnapshotImporter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class RegistryCoverage extends Command
{
    protected $signature = 'registry:coverage';

    protected $description = 'Write national inventories, source references and unresolved research coverage as JSON';

    public function handle(VerifiedSnapshotImporter $importer): int
    {
        $reference = $importer->snapshot('hec-universities');
        $universities = University::withoutGlobalScope('regional_catalog')->withCount(['campuses' => fn ($query) => $query->withoutGlobalScope('regional_catalog')])->orderBy('name')->get();
        $campuses = Campus::withoutGlobalScope('regional_catalog')->with(['university' => fn ($query) => $query->withoutGlobalScope('regional_catalog')->select('id', 'name', 'slug')])->orderBy('university_id')->orderBy('name')->get();
        $lastRun = Storage::disk('local')->exists('registry/last-import.json')
            ? json_decode(Storage::disk('local')->get('registry/last-import.json'), true) : null;
        $report = [
            'generated_at' => now()->toIso8601String(), 'reference_verified_at' => $reference['verified_at'],
            'reference_sha256' => $reference['content_sha256'], 'nationally_complete' => false,
            'allowed_regions' => config('uniguide.allowed_regions'),
            'active_catalog' => ['universities' => University::count(), 'campuses' => Campus::count(),
                'numl_campuses' => Campus::whereHas('university', fn ($query) => $query->where('slug', 'numl'))->count()],
            'official_reference_institutions' => count($reference['universities']),
            'universities_imported' => $universities->whereNotNull('registry_key')->count(),
            'universities_verified' => $universities->where('verification_status', 'verified')->count(),
            'campuses_total_in_database' => $campuses->count(),
            'campuses_imported_with_provenance' => $campuses->whereNotNull('registry_key')->count(),
            'campuses_verified' => $campuses->where('verification_status', 'verified')->count(),
            'campuses_with_unknown_operating_status' => $campuses->where('operational_status', 'unknown')->count(),
            'outdated_sources' => DataSource::withoutGlobalScope('regional_catalog')->whereNotNull('registry_key')->where('verified_at', '<', now()->subYear())->count(),
            'universities_with_incomplete_campus_data' => $universities->where('campus_coverage', '!=', 'complete')->count(),
            'duplicate_inserts_avoided_last_national_run' => $lastRun['national']['matched'] ?? null,
            'verification_status_counts' => $universities->countBy('verification_status')->all(),
            'numl_campus_count' => $campuses->where('university.slug', 'numl')->count(),
            'by_province' => $universities->groupBy('province')->map(fn ($items, string $province): array => [
                'institutions' => $items->count(), 'verified' => $items->where('verification_status', 'verified')->count(),
                'campuses_by_institution_headquarters' => $items->sum('campuses_count'),
                'campuses_by_physical_location' => $campuses->filter(function ($campus) use ($province): bool {
                    $aliases = array_change_key_case(VerifiedSnapshotImporter::PROVINCES, CASE_LOWER) + ['islamabad (ict)' => 'ict'];
                    $value = strtolower($campus->province ?? '');

                    return ($aliases[$value] ?? $value) === $province;
                })->count(),
            ])->all(),
            'sources' => DataSource::withoutGlobalScope('regional_catalog')->whereNotNull('registry_key')->get(['source_url', 'source_type', 'fetched_at', 'content_sha256'])->toArray(),
            'universities' => $universities->toArray(), 'campuses' => $campuses->toArray(),
            'manual_review' => $universities->map(fn ($university): array => [
                'id' => $university->id, 'name' => $university->name, 'slug' => $university->slug,
                'campus_coverage' => $university->campus_coverage,
                'reason' => $university->slug === 'numl'
                    ? 'Directory cross-checked; Rawalpindi contact and operating status unresolved; contact currency and Mirpur profile discrepancy require review.'
                    : 'Official university website and full campus directory not yet researched; do not infer campuses from HEC institution listing.',
            ])->all(),
            'conflicts' => UniversitySourceRecord::withoutGlobalScope('regional_catalog')->where('verification_status', 'conflicting_information')->get()->toArray(),
            'limitations' => ['HEC-listed identity is distinct from independent campus recognition and current operational status.',
                'Legacy records without registry keys are not counted as verified.',
                'National websites and non-NUML campus inventories remain pending research.',
                'Inventory includes preserved archives; active_catalog describes the current configured regional release.',
                'Counts describe this source snapshot and local database, not guaranteed current national completeness.'],
        ];
        Storage::disk('local')->put('registry/coverage.json', json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));
        $this->info('Coverage report: '.Storage::disk('local')->path('registry/coverage.json'));
        $this->line(json_encode(collect($report)->only(['official_reference_institutions', 'universities_imported', 'universities_verified', 'campuses_verified', 'numl_campus_count', 'verification_status_counts'])));

        return self::SUCCESS;
    }
}
