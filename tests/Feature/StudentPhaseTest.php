<?php

namespace Tests\Feature;

use App\Models\Campus;
use App\Models\Program;
use App\Models\StudentApplication;
use App\Models\University;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\DatabaseFeatureTestCase;

class StudentPhaseTest extends DatabaseFeatureTestCase
{
    private function program(): Program
    {
        $u = University::where('slug', 'numl')->firstOrFail();
        $c = Campus::where('university_id', $u->id)->firstOrFail();

        return Program::create(['university_id' => $u->id, 'campus_id' => $c->id, 'slug' => 'test-degree', 'name' => 'Test degree', 'degree_level' => 'undergraduate', 'discipline' => 'computing', 'duration_years' => 4]);
    }

    public function test_student_pages_are_private_and_empty_state_is_not_a_demo_profile(): void
    {
        $this->get('/student/dashboard')->assertRedirect();
        $this->loginAs('student@gmail.com');
        foreach (['dashboard', 'profile', 'recommendations', 'saved/universities', 'saved/programs', 'saved/scholarships', 'tracker', 'notifications', 'settings', 'onboarding'] as $path) {
            $this->get('/student/'.$path)->assertOk()->assertDontSee('61101-9876543-1')->assertDontSee('77.95%');
        }
        $this->loginAs('admin@gmail.com');
        $this->get('/student/dashboard')->assertForbidden();
    }

    public function test_profile_updates_and_notification_preferences_survive_session_changes(): void
    {
        $user = $this->loginAs('student@gmail.com');
        $this->post('/student/profile', ['full_name' => 'Updated student', 'phone' => '03001234567', 'city' => 'Multan', 'matric_percentage' => 83, 'fsc_percentage' => 79])->assertRedirect();
        $this->assertSame('Updated student', $user->fresh()->name);
        $this->assertSame(79, $user->profile()->first()->fsc_data['percentage']);
        $this->post('/student/settings', ['full_name' => 'Updated student', 'phone' => '03001234567', 'city' => 'Multan', 'deadline_reminders' => '1'])->assertRedirect();
        $this->assertTrue($user->profile()->first()->preferences['deadline_reminders']);
    }

    public function test_tracker_crud_is_owned_by_the_current_student(): void
    {
        $user = $this->loginAs('student@gmail.com');
        $p = $this->program();
        $this->post('/student/tracker', ['program_id' => $p->id, 'stage' => 'interested'])->assertRedirect();
        $a = StudentApplication::firstOrFail();
        $this->assertSame($user->id, $a->user_id);
        $this->put('/student/tracker/'.$a->id, ['stage' => 'applied', 'user_id' => 999])->assertRedirect();
        $this->assertSame('applied', $a->fresh()->stage);
        $this->assertSame($user->id, $a->fresh()->user_id);
        $other = User::factory()->create(['role' => 'student']);
        $this->actingAs($other)->put('/student/tracker/'.$a->id, ['stage' => 'closed'])->assertNotFound();
        $this->delete('/student/tracker/'.$a->id)->assertNotFound();
        $this->actingAs($user)->delete('/student/tracker/'.$a->id)->assertRedirect();
        $this->assertSame(0, StudentApplication::count());
    }

    public function test_saved_program_toggle_is_persistent_and_uses_real_record_metadata(): void
    {
        $user = $this->loginAs('student@gmail.com');
        $p = $this->program();
        $this->post('/student/saved/toggle', ['item_type' => 'program', 'item_slug' => $p->slug])->assertRedirect();
        $this->assertSame(1, $user->savedItems()->count());
        $p->update(['name' => 'Renamed program']);
        $this->get('/student/saved/programs')->assertOk()->assertSee('Renamed program');
        $this->post('/student/saved/toggle', ['item_type' => 'program', 'item_slug' => $p->slug])->assertRedirect();
        $this->assertSame(0, $user->savedItems()->count());
    }

    public function test_private_documents_and_account_export_do_not_fake_downloads(): void
    {
        Storage::fake('local');
        $user = $this->loginAs('student@gmail.com');
        $this->post('/student/documents', ['title' => 'Academic result', 'document' => UploadedFile::fake()->create('result.pdf', 10, 'application/pdf')])->assertRedirect();
        $doc = $user->profile()->first()->documents_data[0];
        Storage::disk('local')->assertExists($doc['path']);
        $this->get('/student/documents/'.$doc['id'])->assertOk();
        $this->get('/student/export')->assertOk()->assertDownload('uniguide-account.json');
        $this->actingAs(User::factory()->create(['role' => 'student']))->get('/student/documents/'.$doc['id'])->assertNotFound();
    }

    public function test_account_password_changes_require_the_current_password_and_inactive_accounts_cannot_log_in(): void
    {
        $user = $this->loginAs('student@gmail.com');
        $this->post('/account/password', ['current_password' => 'wrong', 'password' => 'NewPass123!', 'password_confirmation' => 'NewPass123!'])->assertSessionHasErrors('current_password');
        $this->post('/account/password', ['current_password' => '12345678', 'password' => 'NewPass123!', 'password_confirmation' => 'NewPass123!'])->assertRedirect();
        $this->assertTrue(Hash::check('NewPass123!', $user->fresh()->password));
        $user->update(['status' => 'inactive']);
        $this->post('/logout');
        $this->post('/login', ['email' => $user->email, 'password' => 'NewPass123!'])->assertSessionHasErrors('email');
    }
}
