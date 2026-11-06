<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with verified regional university snapshots and active catalog data.
     */
    public function run(): void
    {
        $this->call([
            NumlUniversitySeeder::class,
            NationalUniversitySeeder::class,
            UniversitySeeder::class,
            AdmissionSeeder::class,
            ScholarshipSeeder::class,
            EligibilityRuleSeeder::class,
            UserSeeder::class,
            StudentSeeder::class,
            NationalAdminSeeder::class,
        ]);
    }
}
