<?php

namespace Database\Seeders;

use App\Services\UniversityImport\VerifiedSnapshotImporter;
use Illuminate\Database\Seeder;

class NationalCampusSeeder extends Seeder
{
    public function run(): void
    {
        app(VerifiedSnapshotImporter::class)->importNuml();
    }
}
