<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Student Demo Persona
        User::updateOrCreate(
            ['email' => 'ahmad.raza.khan@student.pk'],
            [
                'name' => 'Ahmad Raza Khan',
                'password' => Hash::make('Student@UniGuide2026!'),
                'role' => 'student',
                'institution_slug' => null,
                'phone' => '+92 300 5558841',
                'province' => 'ict',
                'two_factor_enabled' => true,
                'status' => 'active',
            ]
        );

        User::updateOrCreate(
            ['email' => 'ahmad.raza@student.uniguide.pk'],
            [
                'name' => 'Ahmad Raza Khan',
                'password' => Hash::make('StudentPass2026!'),
                'role' => 'student',
                'institution_slug' => null,
                'phone' => '+92 300 5558841',
                'province' => 'ict',
                'two_factor_enabled' => true,
                'status' => 'active',
            ]
        );

        // 2. University Administrator Demo Persona (NUST)
        User::updateOrCreate(
            ['email' => 'tariq.mahmood@nust.edu.pk'],
            [
                'name' => 'Dr. Tariq Mahmood',
                'password' => Hash::make('NustAdmin@2026!'),
                'role' => 'university_admin',
                'institution_slug' => 'nust',
                'phone' => '+92 51 9085 1000',
                'province' => 'ict',
                'two_factor_enabled' => true,
                'status' => 'active',
            ]
        );

        User::updateOrCreate(
            ['email' => 'admissions@nust.edu.pk'],
            [
                'name' => 'NUST Admissions Directorate',
                'password' => Hash::make('NustAdmin2026!'),
                'role' => 'university_admin',
                'institution_slug' => 'nust',
                'phone' => '+92 51 9085 1000',
                'province' => 'ict',
                'two_factor_enabled' => true,
                'status' => 'active',
            ]
        );

        // 3. University Staff Demo Persona (NUST Registrar)
        User::updateOrCreate(
            ['email' => 'registrar@nust.edu.pk'],
            [
                'name' => 'Prof. Naveed Arshad',
                'password' => Hash::make('NustStaff@2026!'),
                'role' => 'university_staff',
                'institution_slug' => 'nust',
                'phone' => '+92 51 9085 1005',
                'province' => 'ict',
                'two_factor_enabled' => true,
                'status' => 'active',
            ]
        );

        // 4. National Super Administrator (HEC Chairman)
        User::updateOrCreate(
            ['email' => 'chairman@hec.gov.pk'],
            [
                'name' => 'Prof. Dr. Mukhtar Ahmed',
                'password' => Hash::make('HecFederal@2026!'),
                'role' => 'national_admin',
                'institution_slug' => null,
                'phone' => '+92 51 9040 0000',
                'province' => 'ict',
                'two_factor_enabled' => true,
                'status' => 'active',
            ]
        );

        User::updateOrCreate(
            ['email' => 'regulatory@hec.gov.pk'],
            [
                'name' => 'HEC National Regulatory Command',
                'password' => Hash::make('HecFederal2026!'),
                'role' => 'national_admin',
                'institution_slug' => null,
                'phone' => '+92 51 9040 0000',
                'province' => 'ict',
                'two_factor_enabled' => true,
                'status' => 'active',
            ]
        );

        // 5. Generic National Admin for testing
        User::updateOrCreate(
            ['email' => 'admin@uniguide.pk'],
            [
                'name' => 'UniGuide National Regulator',
                'password' => Hash::make('SuperAdmin@UniGuide2026!'),
                'role' => 'national_admin',
                'institution_slug' => null,
                'phone' => '+92 51 9000 0000',
                'province' => 'ict',
                'two_factor_enabled' => true,
                'status' => 'active',
            ]
        );
    }
}
