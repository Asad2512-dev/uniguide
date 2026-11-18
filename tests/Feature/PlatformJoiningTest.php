<?php

namespace Tests\Feature;

use App\Models\University;
use App\Models\UniversityApplication;
use App\Models\User;
use Database\Seeders\NumlUniversitySeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PlatformJoiningTest extends TestCase
{
    public function test_previous_admin_urls_redirect_to_platform_urls(): void
    {
        $this->get('/admin/national/users?status=active')
            ->assertStatus(308)
            ->assertRedirect('/admin/platform/users?status=active');
        $this->post('/admin/national/settings', ['crawler_concurrency' => 2])
            ->assertStatus(308)
            ->assertRedirect('/admin/platform/settings');
    }

    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Artisan::call('migrate', ['--database' => 'sqlite', '--force' => true]);
    }

    private function submission(): array
    {
        return ['name' => 'Multan Example University', 'website' => 'https://example.edu.pk', 'city' => 'Multan', 'province' => 'punjab', 'sector' => 'private', 'contact_name' => 'Admissions Team', 'email' => 'admissions@example.edu.pk', 'phone' => '03001234567', 'description' => 'A university offering undergraduate programs and admissions support in Multan.', 'consent' => '1'];
    }

    public function test_demo_accounts_are_repeatable_and_login_to_the_correct_workspace(): void
    {
        $this->seed(UserSeeder::class);
        $this->seed(UserSeeder::class);
        $this->assertSame(3, User::count());
        foreach (['student@gmail.com' => 'student.dashboard', 'numl@gmail.com' => 'uni_admin.dashboard', 'admin@gmail.com' => 'national_admin.dashboard'] as $email => $route) {
            $this->assertTrue(Hash::check('12345678', User::where('email', $email)->firstOrFail()->password));
            $this->post('/login', ['email' => $email, 'password' => '12345678'])->assertRedirect(route($route));
            $this->post('/logout');
        }
        $this->assertSame('super_admin', User::where('email', 'admin@gmail.com')->value('role'));
        $this->actingAs(User::where('email', 'admin@gmail.com')->firstOrFail())->get('/admin/platform/dashboard')->assertOk()->assertSee('Platform overview')->assertDontSee('Federal');
    }

    public function test_numl_demo_displays_its_own_institution(): void
    {
        $this->seed(NumlUniversitySeeder::class);
        $this->seed(UserSeeder::class);
        $this->actingAs(User::where('email', 'numl@gmail.com')->firstOrFail())
            ->get('/admin/university/dashboard')->assertOk()->assertSee('Multan Campus');
        $this->get('/admin/university/profile')->assertOk();
    }

    public function test_student_advisor_stays_inside_the_student_workspace(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        foreach (['/counselor', '/counselor/chat', '/counselor/history'] as $path) {
            $this->actingAs($student)->get($path)->assertOk()
                ->assertSee('Student Portal')->assertSee('Student mobile navigation')
                ->assertSee('data-workspace-bottom-selector', false)
                ->assertSee('Open conversation history')->assertDontSee('aria-label="Conversation navigation"', false);
        }
        $this->post('/logout');
        $this->get('/counselor')->assertOk()->assertDontSee('Student mobile navigation')
            ->assertSee('aria-label="Conversation navigation"', false);
    }

    public function test_joining_form_and_demo_signin_render(): void
    {
        $this->get('/universities/join')->assertOk()->assertSee('Submit listing request');
        $this->get('/login')->assertOk()->assertSee('numl@gmail.com')->assertSee('admin@gmail.com')->assertDontSee('hec.gov.pk');
    }

    public function test_application_is_saved_then_approved_into_visible_directory(): void
    {
        $this->post('/universities/join', $this->submission())->assertRedirect(route('universities.join'))->assertSessionHas('application_reference');
        $application = UniversityApplication::firstOrFail();
        $this->assertSame('pending', $application->status);
        $this->assertSame(0, University::count());
        $admin = User::factory()->create(['role' => 'super_admin']);
        $this->actingAs($admin)->get('/admin/platform/applications')->assertOk()->assertSee($application->name);
        $this->post('/admin/platform/applications/'.$application->id.'/review', ['decision' => 'approved'])->assertRedirect();
        $this->assertSame('approved', $application->fresh()->status);
        $this->assertSame(1, University::where('name', $application->name)->count());
        $this->post('/admin/platform/applications/'.$application->id.'/review', ['decision' => 'approved'])->assertStatus(409);
        $this->assertSame(1, University::count());
    }

    public function test_rejection_does_not_publish_and_students_cannot_review(): void
    {
        $application = UniversityApplication::create($this->submissionWithoutConsent() + ['reference' => 'UG-TEST']);
        $this->actingAs(User::factory()->create(['role' => 'student']))->post('/admin/platform/applications/'.$application->id.'/review', ['decision' => 'approved'])->assertForbidden();
        $this->actingAs(User::factory()->create(['role' => 'super_admin']))->post('/admin/platform/applications/'.$application->id.'/review', ['decision' => 'rejected'])->assertRedirect();
        $this->assertSame('rejected', $application->fresh()->status);
        $this->assertSame(0, University::count());
    }

    private function submissionWithoutConsent(): array
    {
        $data = $this->submission();
        unset($data['consent']);

        return $data;
    }

    public function test_guests_cannot_access_admin_workspaces_and_invalid_submissions_fail(): void
    {
        $this->get('/admin/platform/dashboard')->assertRedirect(route('auth.login', ['role' => 'admin']));
        $this->get('/admin/university/dashboard')->assertRedirect(route('auth.login', ['role' => 'university']));
        $data = $this->submission();
        $data['website'] = 'javascript:alert(1)';
        $data['consent'] = '0';
        $this->post('/universities/join', $data)->assertSessionHasErrors(['website', 'consent']);
        $this->assertSame(0, UniversityApplication::count());
    }
}
