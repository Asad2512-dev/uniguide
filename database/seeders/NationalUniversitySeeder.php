<?php

namespace Database\Seeders;

use App\Services\UniversityImport\VerifiedSnapshotImporter;
use Illuminate\Database\Seeder;

class NationalUniversitySeeder extends Seeder
{
    public function run(): void
    {
        app(VerifiedSnapshotImporter::class)->importNational();
    }
}
