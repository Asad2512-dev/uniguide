<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\SavedItem;
use App\Models\StudentApplication;
use App\Models\StudentProfile;
use App\Models\User;
use App\Services\StudentService;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $studentUsers = User::where('role', 'student')->get();
        if ($studentUsers->isEmpty()) {
            return;
        }

        $studentService = app(StudentService::class);
        $profile = $studentService->getProfile();
        $applications = $studentService->getTrackerApplications();
        $savedUnis = $studentService->getSavedUniversities();
        $savedProgs = $studentService->getSavedPrograms();
        $savedSchs = $studentService->getSavedScholarships();
        $notifications = $studentService->getNotifications();

        foreach ($studentUsers as $studentUser) {
            // 1. Student Profile
            StudentProfile::updateOrCreate(
                ['user_id' => $studentUser->id],
                [
                    'applicant_id' => 'UG-2026-88'.str_pad($studentUser->id, 2, '0', STR_PAD_LEFT),
                    'full_name' => $studentUser->name ?? $profile['full_name'],
                    'phone' => $studentUser->phone ?? ($profile['phone'] ?? '+92 300 5558841'),
                    'city' => $profile['city'] ?? 'Rawalpindi',
                    'domicile_province' => $profile['domicile_province'] ?? 'ict',
                    'domicile_label' => $profile['domicile_label'] ?? 'Islamabad Capital Territory',
                    'cnic' => $profile['cnic'] ?? '61101-9482104-3',
                    'gender' => $profile['gender'] ?? 'Male',
                    'dob' => '2007-04-12',
                    'stream' => $profile['stream'] ?? 'pre_engineering',
                    'stream_label' => $profile['stream_label'] ?? 'FSc Pre-Engineering',
                    'monthly_household_income' => $profile['monthly_household_income'] ?? 45000,
                    'matric_data' => $profile['matric'] ?? [],
                    'fsc_data' => $profile['fsc'] ?? [],
                    'entry_tests_data' => $profile['entry_tests'] ?? [],
                    'preferences' => [
                        'preferred_cities' => ['Islamabad', 'Rawalpindi', 'Lahore'],
                        'budget_limit' => 250000,
                        'interest_fields' => ['computing', 'artificial_intelligence', 'software_engineering'],
                    ],
                    'onboarding_completed' => true,
                ]
            );

            // 2. Applications (Admission Tracker)
            foreach ($applications as $app) {
                StudentApplication::updateOrCreate(
                    [
                        'user_id' => $studentUser->id,
                        'application_ref' => $app['application_ref'],
                    ],
                    [
                        'university_name' => $app['university_name'],
                        'university_acronym' => $app['university_acronym'] ?? null,
                        'university_slug' => $app['university_slug'] ?? null,
                        'program_name' => $app['program_name'],
                        'campus' => $app['campus'] ?? null,
                        'stage' => $app['stage'] ?? 'applied',
                        'stage_label' => $app['stage_label'] ?? 'Applied',
                        'deadline' => $app['deadline'] ?? null,
                        'deadline_date' => ! empty($app['deadline_date']) ? $app['deadline_date'] : null,
                        'fee_challan_status' => $app['fee_challan_status'] ?? 'Paid',
                        'test_date' => $app['test_date'] ?? null,
                        'calculated_aggregate' => $app['calculated_aggregate'] ?? 77.95,
                        'closing_cutoff_ref' => $app['closing_cutoff_ref'] ?? null,
                        'notes' => $app['notes'] ?? null,
                    ]
                );
            }

            // 3. Saved Universities
            foreach ($savedUnis as $uni) {
                SavedItem::updateOrCreate(
                    [
                        'user_id' => $studentUser->id,
                        'item_type' => 'university',
                        'item_slug' => $uni['slug'],
                    ],
                    [
                        'metadata' => $uni,
                    ]
                );
            }

            // 4. Saved Programs
            foreach ($savedProgs as $prog) {
                SavedItem::updateOrCreate(
                    [
                        'user_id' => $studentUser->id,
                        'item_type' => 'program',
                        'item_slug' => $prog['slug'],
                    ],
                    [
                        'metadata' => $prog,
                    ]
                );
            }

            // 5. Saved Scholarships
            foreach ($savedSchs as $sch) {
                SavedItem::updateOrCreate(
                    [
                        'user_id' => $studentUser->id,
                        'item_type' => 'scholarship',
                        'item_slug' => $sch['slug'],
                    ],
                    [
                        'metadata' => $sch,
                    ]
                );
            }

            // 6. Student Notifications
            foreach ($notifications as $n) {
                Notification::updateOrCreate(
                    [
                        'user_id' => $studentUser->id,
                        'title' => $n['title'],
                    ],
                    [
                        'message' => $n['message'] ?? null,
                        'category' => $n['category'] ?? 'general',
                        'read_at' => ! empty($n['read']) ? now() : null,
                        'dispatched_at' => now()->subHours(2),
                        'status' => 'active',
                    ]
                );
            }
        }
    }
}
