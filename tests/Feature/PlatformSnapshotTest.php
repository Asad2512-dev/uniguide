<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\SeedData\PlatformSnapshot;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\DatabaseFeatureTestCase;

class PlatformSnapshotTest extends DatabaseFeatureTestCase
{
    public function test_snapshot_restores_current_records_and_is_repeatable_without_resetting_edits(): void
    {
        Artisan::call('migrate:fresh', ['--force' => true]);
        $this->seed(DatabaseSeeder::class);
        $snapshot = json_decode(file_get_contents(database_path('data/platform-seed.json')), true, 512, JSON_THROW_ON_ERROR);
        foreach ($snapshot['tables'] as $table => $data) {
            $this->assertSame(count($data['rows']), DB::table($table)->count(), $table);
        }
        $this->assertSame(3, User::count());
        foreach (User::all() as $user) {
            $this->assertContains($user->email, PlatformSnapshot::ACCOUNTS);
            $this->assertTrue(Hash::check('12345678', $user->password));
        }
        User::where('email', 'student@gmail.com')->update(['name' => 'Student updated through profile']);
        $this->seed(DatabaseSeeder::class);
        $this->assertSame('Student updated through profile', User::where('email', 'student@gmail.com')->value('name'));
        foreach ($snapshot['tables'] as $table => $data) {
            $this->assertSame(count($data['rows']), DB::table($table)->count(), $table);
        }
        $this->assertSame([], DB::select('PRAGMA foreign_key_check'));
        foreach (app(PlatformSnapshot::class)->export()['tables']['users']['rows'] as $user) {
            $this->assertArrayNotHasKey('password', $user);
            $this->assertArrayNotHasKey('remember_token', $user);
        }
    }
}
