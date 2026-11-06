<?php

namespace App\Console\Commands;

use App\Models\Admission;
use App\Models\Campus;
use App\Models\DataSource;
use App\Models\KnowledgeBaseDocument;
use App\Models\Program;
use App\Models\Scholarship;
use App\Models\University;
use App\Services\RegionCatalog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RegionalCoverage extends Command
{
    protected $signature = 'uniguide:coverage {--apply : Backfill unambiguous normalized city links; never delete records} {--output= : Private JSON report path}';

    protected $description = 'Report regional release impact; safely normalize existing geography with --apply';

    public function handle(RegionCatalog $catalog): int
    {
        $changes = [];
        foreach (['universities', 'campuses'] as $table) {
            foreach (DB::table($table)->whereNull('city_id')->get() as $row) {
                $region = $catalog->normalizeRegion($row->province);
                $cityIds = DB::table('cities')->join('provinces', 'provinces.id', '=', 'cities.province_id')
                    ->whereIn('provinces.slug', $region ? $catalog->provinceSlugs($region) : [])
                    ->whereRaw('LOWER(cities.name) = ?', [mb_strtolower(trim($row->city ?? ''))])->pluck('cities.id');
                if ($cityIds->count() === 1) {
                    $changes[] = ['table' => $table, 'id' => $row->id, 'city_id' => $cityIds->first()];
                }
            }
        }
        if ($this->option('apply')) {
            DB::transaction(function () use ($changes): void {
                foreach ($changes as $change) {
                    DB::table($change['table'])->where('id', $change['id'])->whereNull('city_id')->update(['city_id' => $change['city_id']]);
                }
            });
        }
        $report = ['generated_at' => now('Asia/Karachi')->toIso8601String(), 'mode' => $this->option('apply') ? 'applied' : 'dry_run',
            'allowed_regions' => $catalog->regions(), 'normalization_changes' => $changes,
            'deletion_plan' => 'No deletions. Historical records remain stored; regional query scopes exclude them from the current catalog.',
            'tables' => [], 'foreign_keys' => []];
        foreach (['universities', 'campuses', 'programs', 'admissions', 'scholarships', 'saved_items', 'student_applications', 'data_sources', 'knowledge_base_documents', 'knowledge_base_chunks', 'ai_messages', 'audit_logs'] as $table) {
            $report['tables'][$table] = ['stored' => DB::table($table)->count()];
            $report['foreign_keys'][$table] = Schema::getForeignKeys($table);
        }
        $report['university_headquarters'] = DB::table('universities')->select('province')->selectRaw('COUNT(*) AS total')->groupBy('province')->get()->all();
        $report['campus_inventory'] = DB::table('campuses')->select('id', 'university_id', 'name', 'city', 'province', 'city_id', 'verification_status')->get()->all();
        $report['unresolved_offering_links'] = ['admissions' => DB::table('admissions')->pluck('id')->all(), 'scholarships' => DB::table('scholarships')->pluck('id')->all()];
        if (Schema::hasColumn('admissions', 'coverage_status')) {
            foreach ([University::class, Campus::class, Program::class, Admission::class, Scholarship::class, DataSource::class, KnowledgeBaseDocument::class] as $model) {
                $table = (new $model)->getTable();
                $ids = $model::pluck('id')->all();
                $report['tables'][$table]['active'] = count($ids);
                $report['tables'][$table]['excluded_ids'] = DB::table($table)->whereNotIn('id', $ids)->pluck('id')->all();
            }
            foreach ($catalog->regions() as $slug => $label) {
                $report['active_university_headquarters'][$slug] = University::whereHas('location.province', fn ($q) => $q->whereIn('slug', $catalog->provinceSlugs($slug)))->count();
            }
            $report['outside_headquarters_with_eligible_campus'] = University::whereDoesntHave('location.province', fn ($q) => $q->whereIn('slug', $catalog->provinceSlugs()))->pluck('id')->all();
            $report['numl_campuses'] = Campus::whereHas('university', fn ($q) => $q->where('slug', 'numl'))->pluck('name')->all();
            $report['affected_saved_items'] = DB::table('saved_items')->get()->filter(function ($item) use ($catalog): bool {
                $type = rtrim($item->item_type ?? '', 's');
                $id = $item->item_id ?? null;

                return ! $id || ! $catalog->contains($type, (int) $id);
            })->pluck('id')->all();
            $report['affected_student_applications'] = DB::table('student_applications')->whereNotIn('program_id', Program::pluck('id'))->pluck('id')->all();
        }
        $path = $this->option('output') ?: storage_path('app/private/regional-coverage/'.($this->option('apply') ? 'applied' : 'impact').'.json');
        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0700, true);
        }
        file_put_contents($path, json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL);
        chmod($path, 0600);
        $this->info($path);
        $this->line(json_encode($report['tables']));

        return self::SUCCESS;
    }
}
