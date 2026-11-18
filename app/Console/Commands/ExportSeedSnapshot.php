<?php

namespace App\Console\Commands;

use App\Services\SeedData\PlatformSnapshot;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ExportSeedSnapshot extends Command
{
    protected $signature = 'uniguide:export-seed-snapshot {--output= : Destination JSON path}';

    protected $description = 'Export current platform records and the three configured accounts for repeatable database seeding.';

    public function handle(PlatformSnapshot $snapshot): int
    {
        $path = $this->option('output') ?: database_path('data/platform-seed.json');
        $data = $snapshot->export();
        File::ensureDirectoryExists(dirname($path));
        File::put($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR)."\n");
        foreach ($data['tables'] as $table => $definition) {
            $this->line($table.': '.count($definition['rows']));
        }
        $this->info('Seed snapshot saved. Password hashes, sessions, tokens, cache, and queued jobs are excluded.');

        return self::SUCCESS;
    }
}
