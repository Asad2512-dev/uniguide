<?php

namespace Tests;

use App\Models\User;
use Database\Seeders\NumlUniversitySeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

abstract class DatabaseFeatureTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Artisan::call('migrate', ['--force' => true]);
        $this->seed([NumlUniversitySeeder::class, UserSeeder::class]);
    }

    protected function loginAs(string $email): User
    {
        $user = User::where('email', $email)->firstOrFail();
        $this->actingAs($user);

        return $user;
    }
}
