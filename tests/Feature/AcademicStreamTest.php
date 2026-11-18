<?php

namespace Tests\Feature;

use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AcademicStreamTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Artisan::call('migrate', ['--database' => 'sqlite', '--force' => true]);
    }

    public function test_every_signup_stream_is_saved_and_preselected_in_onboarding(): void
    {
        foreach (array_keys(config('academic_streams.options')) as $stream) {
            $response = $this->post('/register', [
                'full_name' => 'Test Student', 'email' => $stream.'@example.com', 'phone' => '03001234567',
                'stream' => $stream, 'province' => 'punjab', 'password' => '12345678',
                'password_confirmation' => '12345678', 'terms' => '1',
            ]);
            $response->assertRedirect(route('student.onboarding'));
            $profile = StudentProfile::where('user_id', auth()->id())->firstOrFail();
            $this->assertSame($stream, $profile->stream);
            $this->assertSame(config('academic_streams.options.'.$stream.'.name'), $profile->stream_label);
            $html = $this->get('/student/onboarding')->assertOk()->getContent();
            $this->assertMatchesRegularExpression('/<input\s[^>]*value="'.preg_quote($stream, '/').'"[^>]*\bchecked\b/s', $html);
            $this->post('/logout');
        }
    }

    public function test_existing_signup_values_are_preselected_and_change_persists_across_sessions(): void
    {
        $user = User::factory()->create(['role' => 'student']);
        $profile = StudentProfile::create(['user_id' => $user->id, 'applicant_id' => 'UG-TEST', 'full_name' => $user->name, 'stream' => 'fsc_pre_med']);
        $html = $this->actingAs($user)->withSession(['uniguide_student_profile' => ['stream' => 'pre_engineering']])->get('/student/onboarding')->assertOk()->getContent();
        $this->assertMatchesRegularExpression('/<input\s[^>]*value="pre_medical"[^>]*\bchecked\b/s', $html);
        $this->post('/student/onboarding', [
            'stream' => 'ics', 'matric_percentage' => 85, 'fsc_percentage' => 80,
            'target_cities' => ['Multan'], 'target_disciplines' => ['Software Engineering'], 'budget_max' => 150000,
        ])->assertRedirect(route('student.dashboard'));
        $this->assertSame('ics', $profile->fresh()->stream);
        $this->assertSame('ICS (Computer Science)', $profile->fresh()->stream_label);
        session()->forget('uniguide_student_profile');
        $html = $this->get('/student/onboarding')->assertOk()->getContent();
        $this->assertMatchesRegularExpression('/<input\s[^>]*value="ics"[^>]*\bchecked\b/s', $html);
    }

    public function test_invalid_stream_is_rejected_and_attempted_choice_survives_other_validation_errors(): void
    {
        $this->post('/register', ['stream' => 'unknown'])->assertSessionHasErrors('stream');
        $user = User::factory()->create(['role' => 'student']);
        StudentProfile::create(['user_id' => $user->id, 'applicant_id' => 'UG-TEST', 'full_name' => $user->name, 'stream' => 'pre_engineering']);
        $this->actingAs($user)->post('/student/onboarding', ['stream' => 'dae'])->assertSessionHasErrors('matric_percentage');
        $html = $this->get('/student/onboarding')->assertOk()->getContent();
        $this->assertMatchesRegularExpression('/<input\s[^>]*value="dae"[^>]*\bchecked\b/s', $html);
        $this->post('/student/onboarding', ['stream' => 'unknown'])->assertSessionHasErrors('stream');
        $this->assertSame('pre_engineering', $user->profile->stream);
    }
}
