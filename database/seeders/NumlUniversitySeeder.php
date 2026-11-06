<?php

namespace Database\Seeders;

use App\Services\UniversityImport\VerifiedSnapshotImporter;
use Illuminate\Database\Seeder;

class NumlUniversitySeeder extends Seeder
{
    public function run(): void
    {
        app(VerifiedSnapshotImporter::class)->importNuml();
    }
}
