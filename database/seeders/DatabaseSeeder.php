<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (is_file(database_path('data/platform-seed.json'))) {
            $this->call(PlatformSnapshotSeeder::class);
        } else {
            $this->call([NumlUniversitySeeder::class, NationalUniversitySeeder::class, UserSeeder::class]);
        }
    }
}
