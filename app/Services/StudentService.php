<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Program;
use App\Models\StudentApplication;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class StudentService
{
    public function __construct(
        protected DiscoveryService $discoveryService,
        protected AdmissionService $admissionService,
        protected CounselorService $counselorService
    ) {}

    protected function getCurrentStudent(): ?User
    {
        $user = auth()->user();
        if ($user && $user->role === 'student') {
            return $user;
        }

        return null;
    }

    /**
     * Retrieve the current student profile (database-backed with rich defaults).
     */
    public function getProfile(): array
    {
        $student = $this->getCurrentStudent();
        $record = $student?->profile()->first();
        $stream = config('academic_streams.aliases.'.($record?->stream ?? ''), $record?->stream ?? '');
        $academic = ['degree' => '', 'board' => '', 'year' => '', 'total_marks' => 0, 'obtained_marks' => 0, 'percentage' => 0, 'grade' => '', 'roll_number' => '', 'verified' => false];

        return [
            'full_name' => $record?->full_name ?? $student?->name ?? '',
            'applicant_id' => $record?->applicant_id ?? '', 'email' => $student?->email ?? '',
            'phone' => $record?->phone ?? $student?->phone ?? '', 'city' => $record?->city ?? '',
            'domicile_province' => $record?->domicile_province ?? $student?->province ?? '',
            'domicile_label' => $record?->domicile_label ?? '', 'cnic' => $record?->cnic ?? '',
            'gender' => $record?->gender ?? '', 'dob' => $record?->dob?->toDateString() ?? '',
            'stream' => $stream, 'stream_label' => config('academic_streams.options.'.$stream.'.name', ''),
            'monthly_household_income' => $record?->monthly_household_income,
            'avatar_initials' => mb_strtoupper(mb_substr($student?->name ?? '', 0, 2)),
            'onboarding_completed' => (bool) $record?->onboarding_completed,
            'matric' => array_replace($academic, $record?->matric_data ?? []),
            'fsc' => array_replace($academic + ['expected_part2_percentage' => 0], $record?->fsc_data ?? []),
            'entry_tests' => $record?->entry_tests_data ?? [], 'documents' => $record?->documents_data ?? [],
            'preferences' => array_replace(['target_cities' => [], 'target_disciplines' => [], 'budget_max' => null, 'scholarship_interest' => ''], $record?->preferences ?? []),
        ];
    }

    /**
     * Save updated student profile into database and session.
     */
    public function updateProfile(array $data): array
    {
        $student = $this->getCurrentStudent();
        abort_unless($student, 403);
        if (isset($data['stream'])) {
            $data['stream'] = config('academic_streams.aliases.'.$data['stream'], $data['stream']);
            $data['stream_label'] = config('academic_streams.options.'.$data['stream'].'.name');
        }
        $updated = array_replace_recursive($this->getProfile(), $data);
        StudentProfile::updateOrCreate(['user_id' => $student->id], [
            'applicant_id' => $updated['applicant_id'] ?: 'UG-'.str_pad($student->id, 8, '0', STR_PAD_LEFT),
            'full_name' => $updated['full_name'], 'phone' => $updated['phone'], 'city' => $updated['city'],
            'domicile_province' => $updated['domicile_province'], 'domicile_label' => $updated['domicile_label'],
            'cnic' => $updated['cnic'] ?: null, 'gender' => $updated['gender'] ?: null,
            'stream' => $updated['stream'] ?: 'pre_engineering', 'stream_label' => $updated['stream_label'],
            'monthly_household_income' => $updated['monthly_household_income'],
            'matric_data' => $updated['matric'], 'fsc_data' => $updated['fsc'],
            'entry_tests_data' => $updated['entry_tests'], 'preferences' => $updated['preferences'],
            'onboarding_completed' => $updated['onboarding_completed'],
        ]);
        $student->update(['name' => $updated['full_name'], 'phone' => $updated['phone']]);
        $student->unsetRelation('profile');

        return $this->getProfile();
    }

    /**
     * Compute academic profile completeness percentage and missing checklist.
     */
    public function getCompleteness(): array
    {
        $profile = $this->getProfile();
        $score = 0;
        $missing = [];

        // Matric check
        if (! empty($profile['matric']['obtained_marks'])) {
            $score += 20;
        } else {
            $missing[] = 'Enter your Matriculation / O-Level total and obtained marks';
        }

        // FSc check
        if (! empty($profile['fsc']['obtained_marks'])) {
            $score += 25;
        } else {
            $missing[] = 'Enter your FSc Part-1 / A-Level marks';
        }

        // Entry tests check
        if (! empty($profile['entry_tests'])) {
            $score += 25;
        } else {
            $missing[] = 'Record at least one entry test score (NET, ECAT, or NAT)';
        }

        // Documents check
        $docs = $profile['documents'] ?? [];
        $verifiedDocs = count(array_filter($docs, fn ($d) => ($d['status'] ?? '') === 'verified'));
        if ($verifiedDocs >= 3) {
            $score += 20;
        } else {
            $missing[] = 'Upload attested copies of required admission documents';
        }

        // Income / Aid check
        if (! empty($profile['monthly_household_income'])) {
            $score += 10;
        } else {
            $missing[] = 'Declare monthly household income for scholarship qualification';
        }

        return [
            'percentage' => min(100, $score),
            'missing_items' => $missing,
            'is_ready_for_admissions' => $score >= 75,
        ];
    }

    /**
     * Retrieve student's personal admission tracker applications from database.
     */
    public function getTrackerApplications(): array
    {
        $student = $this->getCurrentStudent();
        if ($student) {
            $dbApps = StudentApplication::where('user_id', $student->id)->get();
            if ($dbApps->isNotEmpty()) {
                $mapped = [];
                foreach ($dbApps as $app) {
                    $mapped[] = [
                        'id' => (string) $app->id,
                        'university_name' => $app->university_name,
                        'university_acronym' => $app->university_acronym ?? 'HEC',
                        'university_slug' => $app->university_slug ?? 'uni',
                        'program_name' => $app->program_name,
                        'campus' => $app->campus ?? 'Main Campus',
                        'stage' => $app->stage,
                        'stage_label' => Program::whereKey($app->program_id)->exists() ? ($app->stage_label ?? ucfirst(str_replace('_', ' ', $app->stage))) : 'Archived — outside current coverage',
                        'badge_color' => match ($app->stage) {
                            'offer_received' => 'success',
                            'test_scheduled' => 'warning',
                            'applied' => 'brand',
                            default => 'neutral',
                        },
                        'application_ref' => $app->application_ref,
                        'deadline' => $app->deadline ?? 'Open',
                        'deadline_date' => $app->deadline_date?->format('Y-m-d') ?? null,
                        'fee_challan_status' => $app->fee_challan_status ?? 'Unpaid',
                        'test_date' => $app->test_date ?? 'TBD',
                        'calculated_aggregate' => floatval($app->calculated_aggregate ?? 0.0),
                        'closing_cutoff_ref' => $app->closing_cutoff_ref ?? 'Merit Archive',
                        'notes' => $app->notes ?? '',
                        'last_updated' => $app->updated_at?->diffForHumans() ?? 'Recently',
                    ];
                }

                return $mapped;
            }
        }

        return [];
    }

    /**
     * Add an application to the student admission tracker.
     */
    public function addTrackerApplication(array $data): array
    {
        abort_unless($this->getCurrentStudent(), 401);
        $program = Program::with(['university', 'campus'])->find($data['program_id'] ?? 0);
        if (! $program) {
            throw ValidationException::withMessages(['program_id' => 'Select a recorded offering at a campus in Punjab or Islamabad.']);
        }
        $data = array_replace($data, ['university_id' => $program->university_id, 'program_id' => $program->id, 'university_name' => $program->university->name, 'university_acronym' => $program->university->acronym, 'university_slug' => $program->university->slug, 'program_name' => $program->name, 'campus' => $program->campus->name]);
        $stageLabels = [
            'interested' => 'Interested Shortlist',
            'preparing' => 'Preparing Application',
            'applied' => 'Applied',
            'test_scheduled' => 'Test Scheduled',
            'awaiting_result' => 'Awaiting Merit List',
            'offer_received' => 'Offer Received',
            'closed' => 'Application Closed',
        ];

        $stage = $data['stage'] ?? 'interested';
        $stageLabel = $stageLabels[$stage] ?? 'Interested';
        $student = $this->getCurrentStudent();

        $app = StudentApplication::create([
            'university_id' => $program->university_id, 'program_id' => $program->id,
            'user_id' => $student?->id,
            'application_ref' => $data['application_ref'] ?? ('APP-'.strtoupper(substr(md5(uniqid()), 0, 8))),
            'university_name' => $data['university_name'] ?? 'University of the Punjab',
            'university_acronym' => $data['university_acronym'] ?? 'PU',
            'university_slug' => $data['university_slug'] ?? 'punjab-university',
            'program_name' => $data['program_name'] ?? 'BS Computer Science',
            'campus' => $data['campus'] ?? 'Main Campus, Lahore',
            'stage' => $stage,
            'stage_label' => $stageLabel,
            'deadline' => $program->university->deadline ?? 'Not recorded',
            'deadline_date' => ! empty($data['deadline_date']) ? $data['deadline_date'] : null,
            'fee_challan_status' => $data['fee_challan_status'] ?? 'Unpaid',
            'test_date' => $data['test_date'] ?? 'TBD',
            'calculated_aggregate' => floatval($data['calculated_aggregate'] ?? 0),
            'closing_cutoff_ref' => $data['closing_cutoff_ref'] ?? 'Merit Archive',
            'notes' => $data['notes'] ?? 'Manual admission entry added by student.',
        ]);

        return [
            'id' => (string) $app->id,
            'university_name' => $app->university_name,
            'university_acronym' => $app->university_acronym,
            'university_slug' => $app->university_slug,
            'program_name' => $app->program_name,
            'campus' => $app->campus,
            'stage' => $app->stage,
            'stage_label' => $app->stage_label,
            'badge_color' => match ($app->stage) {
                'offer_received' => 'success',
                'test_scheduled' => 'warning',
                'applied' => 'brand',
                default => 'neutral',
            },
            'application_ref' => $app->application_ref,
            'deadline' => $app->deadline,
            'deadline_date' => $app->deadline_date?->format('Y-m-d'),
            'fee_challan_status' => $app->fee_challan_status,
            'test_date' => $app->test_date,
            'calculated_aggregate' => floatval($app->calculated_aggregate),
            'closing_cutoff_ref' => $app->closing_cutoff_ref,
            'notes' => $app->notes,
            'last_updated' => 'Just now',
        ];
    }

    /**
     * Update an application in the tracker.
     */
    public function updateTrackerApplication(string $id, array $data): ?array
    {
        $student = $this->getCurrentStudent();
        $numericId = (int) str_replace('app-', '', $id);

        $app = StudentApplication::where('user_id', $student?->id)->where(fn ($q) => $q->where('id', $numericId)->orWhere('application_ref', $id))->firstOrFail();

        if ($app) {
            $stageLabels = [
                'interested' => 'Interested Shortlist',
                'preparing' => 'Preparing Application',
                'applied' => 'Applied',
                'test_scheduled' => 'Test Scheduled',
                'awaiting_result' => 'Awaiting Merit List',
                'offer_received' => 'Offer Received',
                'closed' => 'Application Closed',
            ];

            if (isset($data['stage'])) {
                $data['stage_label'] = $stageLabels[$data['stage']] ?? 'Updated';
            }

            $app->update(Arr::only($data, ['stage', 'stage_label', 'notes', 'application_ref', 'test_date', 'fee_challan_status']));

            return [
                'id' => (string) $app->id,
                'university_name' => $app->university_name,
                'university_acronym' => $app->university_acronym,
                'university_slug' => $app->university_slug,
                'program_name' => $app->program_name,
                'campus' => $app->campus,
                'stage' => $app->stage,
                'stage_label' => $app->stage_label,
                'badge_color' => match ($app->stage) {
                    'offer_received' => 'success',
                    'test_scheduled' => 'warning',
                    'applied' => 'brand',
                    default => 'neutral',
                },
                'application_ref' => $app->application_ref,
                'deadline' => $app->deadline,
                'deadline_date' => $app->deadline_date?->format('Y-m-d'),
                'fee_challan_status' => $app->fee_challan_status,
                'test_date' => $app->test_date,
                'calculated_aggregate' => floatval($app->calculated_aggregate),
                'closing_cutoff_ref' => $app->closing_cutoff_ref,
                'notes' => $app->notes,
                'last_updated' => 'Just now',
            ];
        }

        return null;
    }

    /**
     * Remove an application from the tracker.
     */
    public function deleteTrackerApplication(string $id): bool
    {
        $numericId = (int) str_replace('app-', '', $id);
        $application = StudentApplication::where('user_id', $this->getCurrentStudent()?->id)->where(fn ($q) => $q->where('id', $numericId)->orWhere('application_ref', $id))->firstOrFail();
        $deleted = $application->delete();

        return $deleted > 0;
    }

    /**
     * Retrieve student's saved universities collection from database.
     */
    public function getSavedUniversities(string $sort = 'rank'): array
    {
        $items = [];
        if (! $student = $this->getCurrentStudent()) {
            return [];
        }
        foreach ($student->savedItems()->where('item_type', 'university')->get() as $item) {
            $record = $this->discoveryService->getUniversityBySlug($item->item_slug);
            if ($record) {
                $items[] = array_replace($record, ['match_tier' => 'Not assessed']);
            }
        }

        return $items;
    }

    /**
     * Remove university from saved collection.
     */
    public function removeSavedUniversity(string $slug): void
    {
        $this->getCurrentStudent()?->savedItems()->where('item_type', 'university')->where('item_slug', $slug)->delete();
    }

    /**
     * Retrieve student's saved programs collection from database.
     */
    public function getSavedPrograms(): array
    {
        $items = [];
        if (! $student = $this->getCurrentStudent()) {
            return [];
        }
        foreach ($student->savedItems()->where('item_type', 'program')->get() as $item) {
            $record = $this->discoveryService->getProgramBySlug($item->item_slug);
            if ($record) {
                $items[] = array_replace($record, ['name' => $record['title'], 'university_acronym' => $record['university_name'], 'campus' => $record['city'], 'calculated_aggregate' => null, 'closing_merit_ref' => $record['cutoff_merit']]);
            }
        }

        return $items;
    }

    /**
     * Remove program from saved collection.
     */
    public function removeSavedProgram(string $slug): void
    {
        $this->getCurrentStudent()?->savedItems()->where('item_type', 'program')->where('item_slug', $slug)->delete();
    }

    /**
     * Retrieve student's saved scholarships from database.
     */
    public function getSavedScholarships(): array
    {
        $items = [];
        if (! $student = $this->getCurrentStudent()) {
            return [];
        }
        foreach ($student->savedItems()->where('item_type', 'scholarship')->get() as $item) {
            $record = $this->admissionService->getScholarshipBySlug($item->item_slug);
            if ($record) {
                $items[] = array_replace($record, ['name' => $record['title'], 'badge' => 'Saved', 'badge_color' => 'neutral', 'eligibility_status' => 'Not assessed']);
            }
        }

        return $items;
    }

    /**
     * Remove scholarship from saved collection.
     */
    public function removeSavedScholarship(string $slug): void
    {
        $this->getCurrentStudent()?->savedItems()->where('item_type', 'scholarship')->where('item_slug', $slug)->delete();
    }

    /**
     * Retrieve student notifications from database.
     */
    public function getNotifications(string $filter = 'all'): array
    {
        if (! $student = $this->getCurrentStudent()) {
            return [];
        }

        return $student->notifications()->where('status', 'active')->when($filter !== 'all', fn ($q) => $q->where('category', $filter))->latest()->get()->map(fn ($n) => [
            'id' => (string) $n->id, 'type' => $n->category, 'title' => $n->title, 'message' => $n->message ?? '',
            'link' => $n->safeActionUrl() ?? route('notifications.index'), 'time_ago' => $n->created_at?->diffForHumans(),
            'read' => $n->read_at !== null, 'urgent' => $n->category === 'deadline',
        ])->all();
    }

    /**
     * Mark notification as read.
     */
    public function markNotificationRead(string $id): void
    {
        $student = $this->getCurrentStudent();
        if ($student) {
            $numericId = (int) str_replace('notif-', '', $id);
            Notification::where('user_id', $student->id)
                ->where('id', $numericId)
                ->update(['read_at' => now()]);
        }
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllNotificationsRead(): void
    {
        $student = $this->getCurrentStudent();
        if ($student) {
            Notification::where('user_id', $student->id)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }
    }

    /**
     * Retrieve personalized university recommendations tailored to this authenticated student.
     */
    public function getPersonalizedRecommendations(): array
    {
        $profile = $this->getProfile();
        $test = $profile['entry_tests'][0] ?? [];

        return $this->counselorService->evaluateStudent([
            'matric_percentage' => $profile['matric']['percentage'], 'fsc_percentage' => $profile['fsc']['percentage'],
            'test_type' => $test['test_type'] ?? 'none', 'test_score' => $test['score'] ?? 0, 'test_max' => $test['max_score'] ?? 0,
            'budget_max' => $profile['preferences']['budget_max'], 'monthly_income' => $profile['monthly_household_income'],
        ]);
    }
}
