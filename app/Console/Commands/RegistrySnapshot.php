<?php

namespace App\Console\Commands;

use App\Services\UniversityImport\HecUniversityImporter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class RegistrySnapshot extends Command
{
    protected $signature = 'registry:snapshot';

    protected $description = 'Fetch an HEC reference snapshot for review without publishing or importing it';

    public function handle(HecUniversityImporter $importer): int
    {
        $snapshot = $importer->fetchSnapshot();
        $path = 'registry-research/hec-candidate-'.now()->format('Ymd-His').'.json';
        Storage::disk('local')->put($path, json_encode($snapshot, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));
        $this->info(count($snapshot['universities']).' reference rows saved for review: '.Storage::disk('local')->path($path));

        return self::SUCCESS;
    }
}
