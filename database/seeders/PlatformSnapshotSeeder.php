<?php

namespace Database\Seeders;

use App\Services\SeedData\PlatformSnapshot;
use Illuminate\Database\Seeder;

class PlatformSnapshotSeeder extends Seeder
{
    public function run(): void
    {
        app(PlatformSnapshot::class)->restore(database_path('data/platform-seed.json'));
    }
}
