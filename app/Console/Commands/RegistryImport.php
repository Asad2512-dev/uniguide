<?php

namespace App\Console\Commands;

use App\Services\UniversityImport\VerifiedSnapshotImporter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class RegistryImport extends Command
{
    protected $signature = 'registry:import {--national : Import the reviewed national reference snapshot after NUML}';

    protected $description = 'Idempotently import reviewed offline university and campus snapshots';

    public function handle(VerifiedSnapshotImporter $importer): int
    {
        $numl = $importer->importNuml();
        $counts = $this->option('national') ? $importer->importNational() : null;
        Storage::disk('local')->put('registry/last-import.json', json_encode([
            'ran_at' => now()->toIso8601String(), 'connection' => config('database.default'),
            'national' => $counts, 'numl_campuses' => $numl->campuses()->count(),
            'allowed_regions' => config('uniguide.allowed_regions'),
        ], JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR));
        $this->info('NUML imported; national results: '.json_encode($counts));

        return self::SUCCESS;
    }
}
