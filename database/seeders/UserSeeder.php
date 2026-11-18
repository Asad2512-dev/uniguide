<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['email' => 'numl@gmail.com', 'name' => 'NUML Multan Administrator', 'role' => 'university_admin', 'institution_slug' => 'numl'],
            ['email' => 'student@gmail.com', 'name' => 'Demo Student', 'role' => 'student', 'institution_slug' => null],
            ['email' => 'admin@gmail.com', 'name' => 'UniGuide Platform Administrator', 'role' => 'super_admin', 'institution_slug' => null],
        ] as $account) {
            User::updateOrCreate(['email' => $account['email']], array_merge($account, [
                'password' => Hash::make('12345678'), 'province' => 'punjab',
                'status' => 'active', 'two_factor_enabled' => false,
            ]));
        }
    }
}
