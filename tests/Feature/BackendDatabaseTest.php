<?php

namespace Tests\Feature;

use App\Models\Admission;
use App\Models\Campus;
use App\Models\Inquiry;
use App\Models\Notification;
use App\Models\Program;
use App\Models\SavedItem;
use App\Models\Scholarship;
use App\Models\University;
use App\Models\User;
use App\Services\StudentService;
use Database\Seeders\NumlUniversitySeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BackendDatabaseTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Artisan::call('migrate', ['--force' => true]);
        $this->seed([NumlUniversitySeeder::class, UserSeeder::class]);
    }

    public function test_pages_render_from_database_without_sample_records(): void
    {
        $failures = [];
        foreach ([
            'student@gmail.com' => ['/student/dashboard', '/student/profile', '/student/recommendations', '/student/saved/universities', '/student/saved/programs', '/student/saved/scholarships', '/student/tracker', '/student/notifications', '/student/settings', '/counselor', '/counselor/recommendations', '/counselor/search'],
            'numl@gmail.com' => array_map(fn ($p) => '/admin/university/'.$p, ['dashboard', 'profile', 'campuses', 'departments', 'programs', 'fees', 'admissions', 'eligibility', 'scholarships', 'documents', 'ai-verification', 'inquiries', 'settings']),
            'admin@gmail.com' => array_map(fn ($p) => '/admin/platform/'.$p, ['dashboard', 'universities', 'applications', 'programs', 'sources', 'scraping', 'scraping/history', 'ai-extraction', 'verification', 'knowledge-base', 'chatbot-config', 'users', 'notifications', 'analytics', 'settings']),
        ] as $email => $paths) {
            $this->actingAs(User::where('email', $email)->firstOrFail());
            foreach ($paths as $path) {
                $r = $this->get($path);
                if ($r->status() !== 200) {
                    $failures[] = $path.': '.($r->exception?->getMessage() ?? $r->status());
                }
            }
        }
        $this->assertSame([], $failures);
        $this->post('/logout');
        foreach (['/', '/universities', '/programs', '/admissions', '/scholarships', '/search', '/compare/builder'] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_university_changes_are_persisted_and_foreign_records_are_denied(): void
    {
        $this->actingAs(User::where('email', 'numl@gmail.com')->firstOrFail());
        $u = University::where('slug', 'numl')->firstOrFail();
        $this->post('/admin/university/profile', ['name' => $u->name, 'helpline' => '03001234567', 'admissions_email' => 'office@example.edu.pk', 'website' => 'https://numl.edu.pk', 'address' => 'Multan'])->assertRedirect();
        $this->assertSame('office@example.edu.pk', $u->fresh()->email);
        $c = Campus::where('university_id', $u->id)->firstOrFail();
        $p = Program::withoutGlobalScopes()->create(['university_id' => $u->id, 'campus_id' => $c->id, 'slug' => 'db-test', 'name' => 'Database Program', 'degree_level' => 'undergraduate', 'discipline' => 'computing', 'duration_years' => 4, 'fee_per_semester' => 'PKR 100000']);
        $this->put('/admin/university/programs/'.$p->id, ['name' => 'Updated DB Program', 'intake_seats' => 30, 'tuition_semester' => 'PKR 125000'])->assertRedirect();
        $this->assertSame('Updated DB Program', $p->fresh()->name);
        $this->assertSame('PKR 125000', $p->fresh()->fee_per_semester);
        $this->put('/admin/university/programs/999999', ['name' => 'Attack', 'intake_seats' => 30, 'tuition_semester' => 'PKR 1'])->assertNotFound();
    }

    public function test_student_profile_and_saved_items_never_use_session_or_demo_fallbacks(): void
    {
        $user = User::where('email', 'student@gmail.com')->firstOrFail();
        $this->actingAs($user);
        $service = app(StudentService::class);
        $this->assertSame('', $service->getProfile()['cnic']);
        $this->assertSame([], $service->getProfile()['entry_tests']);
        $this->assertSame([], $service->getSavedUniversities());
        $this->assertSame([], $service->getNotifications());
        $u = University::where('slug', 'numl')->firstOrFail();
        $this->post('/eligibility/shortlist', ['slug' => 'numl', 'name' => 'Forged name'])->assertRedirect();
        $this->assertSame($u->id, SavedItem::where('user_id', $user->id)->value('item_id'));
        $this->assertSame($u->name, $service->getSavedUniversities()[0]['name']);
        $this->delete('/student/saved/universities/numl')->assertRedirect();
        $this->assertSame([], $service->getSavedUniversities());
    }

    public function test_new_admissions_are_visible_and_reminders_publish_from_database(): void
    {
        $this->loginForExtraTests('numl@gmail.com');
        $u = University::where('slug', 'numl')->firstOrFail();
        $campus = Campus::where('university_id', $u->id)->firstOrFail();
        $deadline = now()->addDays(20)->toDateString();
        $this->post('/admin/university/admissions', ['title' => 'Recorded admission cycle', 'campus_id' => $campus->id, 'deadline_date' => $deadline, 'status' => 'open', 'session' => 'Test session'])->assertRedirect();
        $admission = Admission::firstOrFail();
        $this->get('/admissions/'.$admission->slug)->assertOk()->assertSee('Recorded admission cycle');
        $this->loginForExtraTests('student@gmail.com');
        $this->post('/student/admissions/'.$admission->slug.'/reminders')->assertRedirect();
        $this->assertSame(2, Notification::where('status', 'scheduled')->count());
        $this->post('/student/admissions/'.$admission->slug.'/reminders')->assertRedirect();
        $this->assertSame(2, Notification::count());
        Notification::query()->update(['dispatched_at' => now()->subMinute()]);
        $this->get('/student/dashboard')->assertOk();
        $this->assertSame(2, Notification::where('status', 'active')->count());
    }

    public function test_entry_test_results_persist_without_claiming_verification(): void
    {
        $this->loginForExtraTests('student@gmail.com');
        $this->post('/student/entry-tests', ['name' => 'Test result', 'test_type' => 'entrance', 'score' => 60, 'max_score' => 100])->assertRedirect();
        $tests = auth()->user()->profile()->first()->entry_tests_data;
        $this->assertSame(60, $tests[0]['score']);
        $this->assertSame('Self-reported', $tests[0]['status']);
        $this->post('/student/entry-tests', ['name' => 'Bad result', 'test_type' => 'entrance', 'score' => 120, 'max_score' => 100])->assertSessionHasErrors('score');
    }

    public function test_retirement_backs_up_data_and_preserves_registered_records_while_disabling_extra_logins(): void
    {
        Storage::fake('local');
        $extra = User::factory()->create(['role' => 'student', 'status' => 'active']);
        $this->artisan('uniguide:retire-demo-data')->assertSuccessful();
        $this->assertSame(3, User::where('status', 'active')->count());
        $this->assertSame('inactive', $extra->fresh()->status);
        $this->assertTrue(University::where('slug', 'numl')->exists());
        $this->assertNotEmpty(Storage::disk('local')->files('backups'));
    }

    public function test_real_catalog_records_render_detail_pages_and_inquiries_route_to_the_institution(): void
    {
        $this->loginForExtraTests('numl@gmail.com');
        $u = University::where('slug', 'numl')->firstOrFail();
        $campus = Campus::where('university_id', $u->id)->firstOrFail();
        $program = Program::create(['university_id' => $u->id, 'campus_id' => $campus->id, 'slug' => 'detail-contract', 'name' => 'Recorded degree', 'degree_level' => 'undergraduate', 'discipline' => 'computing', 'duration_years' => 4]);
        $this->get('/programs/'.$program->slug)->assertOk()->assertSee('Recorded degree');
        $this->post('/admin/university/scholarships', ['name' => 'Recorded aid scheme', 'funding_agency' => 'Institution', 'category' => 'merit', 'coverage' => 'Recorded tuition support', 'annual_budget' => 'PKR 100000', 'beneficiaries_target' => 5, 'eligibility_criteria' => 'Institution review required'])->assertRedirect();
        $scholarship = Scholarship::withoutGlobalScopes()->where('title', 'Recorded aid scheme')->firstOrFail();
        $this->get('/scholarships/'.$scholarship->slug)->assertOk()->assertSee('Recorded aid scheme');
        $this->loginForExtraTests('student@gmail.com');
        $this->post('/contact', ['university_slug' => 'numl', 'name' => auth()->user()->name, 'email' => auth()->user()->email, 'subject' => 'Institution inquiry', 'message' => 'Please clarify the recorded course information.'])->assertRedirect();
        $inquiry = Inquiry::where('subject', 'Institution inquiry')->firstOrFail();
        $this->assertSame($u->id, $inquiry->university_id);
        $this->loginForExtraTests('numl@gmail.com');
        $this->get('/admin/university/inquiries')->assertOk()->assertSee('Institution inquiry');
    }

    private function loginForExtraTests(string $email): void
    {
        $this->actingAs(User::where('email', $email)->firstOrFail());
    }
}
