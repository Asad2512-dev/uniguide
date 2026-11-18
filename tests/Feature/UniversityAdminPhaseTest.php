<?php

namespace Tests\Feature;

use App\Models\Campus;
use App\Models\Department;
use App\Models\Inquiry;
use App\Models\KnowledgeBaseDocument;
use App\Models\Program;
use App\Models\SystemSetting;
use App\Models\University;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\DatabaseFeatureTestCase;

class UniversityAdminPhaseTest extends DatabaseFeatureTestCase
{
    public function test_institution_pages_require_the_correct_role(): void
    {
        $this->get('/admin/university/dashboard')->assertRedirect();
        $this->loginAs('student@gmail.com');
        $this->get('/admin/university/dashboard')->assertForbidden();
        $this->loginAs('numl@gmail.com');
        foreach (['dashboard', 'profile', 'campuses', 'departments', 'programs', 'fees', 'admissions', 'eligibility', 'scholarships', 'documents', 'ai-verification', 'inquiries', 'settings'] as $path) {
            $this->get('/admin/university/'.$path)->assertOk();
        }
        $this->get('/admin/university/campuses?institution=other-university')->assertForbidden();
    }

    public function test_campus_department_program_and_fee_edits_use_database_ids(): void
    {
        $this->loginAs('numl@gmail.com');
        $u = University::where('slug', 'numl')->firstOrFail();
        $this->post('/admin/university/campuses', ['name' => 'New Teaching Campus', 'city' => 'Multan', 'province' => 'punjab', 'land_area' => 'Recorded area', 'student_capacity' => 100, 'hostel_capacity' => 'Not offered', 'head' => 'Test contact'])->assertRedirect();
        $c = Campus::withoutGlobalScopes()->where('name', 'New Teaching Campus')->firstOrFail();
        $this->post('/admin/university/departments', ['name' => 'Computing department', 'acronym' => 'COMP', 'head' => 'Department head', 'head_title' => 'Chair', 'campus' => $c->name, 'faculty_count' => 5, 'phd_faculty_ratio' => 'Not recorded'])->assertRedirect();
        $d = Department::withoutGlobalScopes()->where('name', 'Computing department')->firstOrFail();
        $this->post('/admin/university/programs', ['name' => 'Degree example', 'code' => 'TEST-PROG', 'level' => 'Undergraduate', 'department' => (string) $d->id, 'duration' => '4 Years', 'credit_hours' => 120, 'accreditation' => 'Not reviewed', 'intake_seats' => 25, 'tuition_semester' => 'PKR 100000'])->assertRedirect();
        $p = Program::withoutGlobalScopes()->where('name', 'Degree example')->firstOrFail();
        $this->assertSame($c->id, $p->campus_id);
        $this->assertSame($u->id, $p->university_id);
        $this->put('/admin/university/programs/'.$p->id, ['name' => 'Updated degree', 'intake_seats' => 30, 'tuition_semester' => 'PKR 120000'])->assertRedirect();
        $this->assertSame('PKR 120000', $p->fresh()->fee_per_semester);
        $this->post('/admin/university/fees', ['category_index' => 0, 'tuition' => 140000])->assertRedirect();
        $this->assertSame('PKR 140,000', $p->fresh()->fee_per_semester);
    }

    public function test_document_upload_download_and_inquiry_reply_are_real(): void
    {
        Storage::fake('local');
        $this->loginAs('numl@gmail.com');
        $this->post('/admin/university/documents/upload', ['title' => 'Official prospectus', 'type' => 'prospectus', 'document' => UploadedFile::fake()->create('prospectus.pdf', 20, 'application/pdf')])->assertRedirect();
        $doc = KnowledgeBaseDocument::withoutGlobalScopes()->firstOrFail();
        Storage::disk('local')->assertExists($doc->file_path);
        $this->get('/admin/university/documents/'.$doc->id.'/download')->assertOk();
        $u = University::where('slug', 'numl')->firstOrFail();
        $student = User::where('email', 'student@gmail.com')->firstOrFail();
        $i = Inquiry::create(['user_id' => $student->id, 'university_id' => $u->id, 'institution_slug' => 'numl', 'name' => $student->name, 'email' => $student->email, 'subject' => 'Admission question', 'message' => 'What are the requirements?']);
        $this->post('/admin/university/inquiries/'.$i->id.'/reply', ['reply' => 'Please review the official prospectus.'])->assertRedirect();
        $this->assertSame('resolved', $i->fresh()->status);
        $this->assertSame(1, $student->notifications()->count());
    }

    public function test_merit_formula_and_settings_are_persistent(): void
    {
        $this->loginAs('numl@gmail.com');
        $u = University::where('slug', 'numl')->firstOrFail();
        $this->post('/admin/university/eligibility', ['net_weight' => 50, 'fsc_weight' => 40, 'matric_weight' => 10])->assertRedirect();
        $this->assertSame(50, SystemSetting::get('university.'.$u->id.'.merit_formula')['net_test_weight']);
        $this->post('/admin/university/eligibility', ['net_weight' => 10, 'fsc_weight' => 10, 'matric_weight' => 10])->assertSessionHasErrors('net_weight');
        $this->post('/admin/university/settings', ['notify_email' => 'office@example.com', 'weekly_inquiry_digest' => '1'])->assertRedirect();
        $this->assertSame('office@example.com', SystemSetting::get('university.'.$u->id.'.settings')['notify_email']);
    }
}
