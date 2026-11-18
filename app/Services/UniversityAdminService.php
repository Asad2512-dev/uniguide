<?php

namespace App\Services;

use App\Models\Admission;
use App\Models\AiExtraction;
use App\Models\Campus;
use App\Models\City;
use App\Models\Department;
use App\Models\Inquiry;
use App\Models\KnowledgeBaseDocument;
use App\Models\Notification;
use App\Models\Program;
use App\Models\Province;
use App\Models\Scholarship;
use App\Models\StudentApplication;
use App\Models\StudentProfile;
use App\Models\SystemSetting;
use App\Models\University;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UniversityAdminService
{
    protected function getTargetUniversity(): University
    {
        $user = auth()->user();
        abort_unless($user && ($user->isUniversityAdmin() || $user->isNationalAdmin()), 403);
        $slug = $user->institution_slug;
        if ($user->isNationalAdmin()) {
            $slug = session('uniguide_admin_institution', $slug);
        }
        abort_unless($slug, 403, 'Assign an institution before opening its administration panel.');

        $key = 'uniguide.institution.'.$user->id.'.'.$slug;
        if (! request()->attributes->has($key)) {
            request()->attributes->set($key, University::withoutGlobalScopes()->where('slug', $slug)->firstOrFail());
        }

        return request()->attributes->get($key);
    }

    private function metadata(string $key): array
    {
        $prefix = 'university.'.$this->getTargetUniversity()->id.'.';
        $cacheKey = 'uniguide.metadata.'.auth()->id().'.'.$prefix;
        if (! request()->attributes->has($cacheKey)) {
            request()->attributes->set($cacheKey, SystemSetting::where('key', 'like', $prefix.'%')->get()->mapWithKeys(fn ($setting) => [$setting->key => $setting->value])->all());
        }

        return request()->attributes->get($cacheKey)[$prefix.$key] ?? [];
    }

    private function saveMetadata(string $key, array $data): void
    {
        $prefix = 'university.'.$this->getTargetUniversity()->id.'.';
        SystemSetting::set($prefix.$key, array_replace($this->metadata($key), $data));
        request()->attributes->remove('uniguide.metadata.'.auth()->id().'.'.$prefix);
    }

    private function programs()
    {
        return Program::withoutGlobalScopes()->where('university_id', $this->getTargetUniversity()->id);
    }

    private function campuses()
    {
        return Campus::withoutGlobalScopes()->where('university_id', $this->getTargetUniversity()->id);
    }

    public function getInstitution(): array
    {
        $u = $this->getTargetUniversity();
        $key = 'uniguide.multan.'.$u->id;
        if (! request()->attributes->has($key)) {
            request()->attributes->set($key, $u->slug === 'numl' && $this->campuses()->where('city', 'Multan')->exists());
        }
        $multan = request()->attributes->get($key);

        return array_replace($u->toArray(), [
            'name' => $u->name.($u->slug === 'numl' && $multan ? ' — Multan Campus' : ''),
            'code' => $u->registry_key ?? 'UG-'.$u->id, 'acronym' => $u->acronym ?? '',
            'accreditations' => $u->accreditations ?? [], 'charter_act' => $u->charter_citation ?? '',
            'admissions_portal' => $u->website ?? '', 'admissions_email' => $u->email ?? '',
            'helpline' => $u->phone ?? '', 'verified_date' => $u->last_verified_at?->format('d M Y') ?? 'Not reviewed',
            'chancellor' => '', 'rector' => '',
        ], $this->metadata('profile'));
    }

    public function getDashboardData(): array
    {
        $u = $this->getTargetUniversity();
        $fields = [$u->name, $u->website, $u->email, $u->phone, $u->address];
        $percent = (int) (count(array_filter($fields)) / count($fields) * 100);
        $inquiries = Inquiry::where('university_id', $u->id)->where('status', '!=', 'resolved')->count();
        $pending = AiExtraction::where('institution_slug', $u->slug)->where('status', 'pending')->count();
        $kpis = [];
        foreach ([['Profile Completeness', $percent.'%', 'check-circle'], ['Programs', $this->programs()->count(), 'book-open'], ['Tracked Applications', StudentApplication::where('university_id', $u->id)->count(), 'users'], ['Pending Reviews', $pending, 'file-check']] as [$title,$value,$icon]) {
            $kpis[] = ['title' => $title, 'value' => $value, 'change' => '', 'changeType' => 'neutral', 'icon' => $icon, 'iconColor' => 'brand'];
        }
        $sessions = collect($this->getAdmissionCycles())->map(fn ($c) => ['id' => $c['id'], 'name' => $c['name'], 'status' => $c['status'], 'status_label' => ucfirst($c['status']), 'deadline' => $c['deadline_date'], 'days_left' => $c['deadline_date'] ? (int) now()->diffInDays($c['deadline_date'], false) : null, 'seats' => $c['total_seats'], 'applications_count' => $c['applications_received'], 'test_series' => $c['entry_tests']])->all();

        return ['institution' => $this->getInstitution(), 'profile_completeness' => $percent, 'missing_items' => [], 'kpis' => $kpis, 'active_sessions' => $sessions, 'recent_activity' => [], 'suggested_actions' => [], 'student_inquiries_count' => $inquiries, 'pending_verifications_count' => $pending, 'documents_processed_count' => KnowledgeBaseDocument::withoutGlobalScopes()->where('university_id', $u->id)->count()];
    }

    public function getProfile(): array
    {
        return array_replace($this->getInstitution(), ['name' => $this->getTargetUniversity()->name]);
    }

    public function updateProfile(array $data): void
    {
        $this->getTargetUniversity()->update(['name' => $data['name'], 'phone' => $data['helpline'], 'email' => $data['admissions_email'], 'website' => $data['website'], 'address' => $data['address']]);
        $this->saveMetadata('profile', Arr::only($data, ['chancellor', 'rector']));
    }

    public function getCampuses(): array
    {
        return $this->campuses()->withCount(['programs', 'departments' => fn ($q) => $q->withoutGlobalScopes()])->get()->map(fn ($c) => array_replace([
            'id' => (string) $c->id, 'is_primary' => $c->is_main_campus, 'departments_count' => $c->departments_count,
            'land_area' => 'Not recorded', 'student_capacity' => null, 'hostel_capacity' => 'Not recorded', 'head' => 'Not recorded', 'facilities' => [],
        ], $c->toArray(), ['facilities' => $c->facilities ?? []], $this->metadata('campus.'.$c->id)))->all();
    }

    private function campusLocation(array $data): array
    {
        $region = app(RegionCatalog::class)->normalizeRegion($data['province'] ?? '');
        abort_unless($region, 422, 'Choose a supported province.');
        $province = Province::firstOrCreate(['slug' => $region], ['name' => config('uniguide.region_labels.'.$region, $region)]);
        $city = City::firstOrCreate(['province_id' => $province->id, 'slug' => Str::slug($data['city'])], ['name' => $data['city']]);

        return ['city_id' => $city->id, 'city' => $city->name, 'province' => $region];
    }

    public function storeCampus(array $data): void
    {
        DB::transaction(function () use ($data) {
            $c = Campus::create($this->campusLocation($data) + ['university_id' => $this->getTargetUniversity()->id, 'slug' => Str::slug($data['name']).'-'.Str::lower(Str::random(6)), 'name' => $data['name'], 'address' => $data['address'] ?? null, 'verification_status' => 'pending', 'operational_status' => 'active']);
            $this->saveMetadata('campus.'.$c->id, Arr::only($data, ['land_area', 'student_capacity', 'hostel_capacity', 'head']));
        });
    }

    public function updateCampus(string $id, array $data): void
    {
        $c = $this->campuses()->findOrFail($id);
        $data['province'] = $data['province'] ?? $c->province;
        $c->update($this->campusLocation($data) + Arr::only($data, ['name', 'address']));
        $this->saveMetadata('campus.'.$c->id, Arr::only($data, ['land_area', 'student_capacity', 'hostel_capacity', 'head']));
    }

    public function getDepartments(): array
    {
        return Department::withoutGlobalScopes()->where('university_id', $this->getTargetUniversity()->id)->with('campus')->withSum(['programs' => fn ($q) => $q->withoutGlobalScopes()], 'seat_capacity')->get()->map(fn ($d) => array_replace($d->toArray(), [
            'id' => (string) $d->id, 'head' => $d->head_of_department ?? '', 'head_title' => '', 'acronym' => '', 'faculty_count' => null, 'phd_faculty_ratio' => '', 'undergraduate_seats' => $d->programs_sum_seat_capacity ?? 0, 'campus' => $d->campus?->name ?? '', 'established' => '',
        ], $this->metadata('department.'.$d->id)))->all();
    }

    public function storeDepartment(array $data): void
    {
        $c = $this->campuses()->where(fn ($q) => $q->where('id', is_numeric($data['campus']) ? $data['campus'] : 0)->orWhere('name', $data['campus']))->firstOrFail();
        $d = Department::create(['university_id' => $this->getTargetUniversity()->id, 'campus_id' => $c->id, 'name' => $data['name'], 'head_of_department' => $data['head'], 'status' => 'active']);
        $this->saveMetadata('department.'.$d->id, Arr::only($data, ['acronym', 'head_title', 'faculty_count', 'phd_faculty_ratio']));
    }

    public function getPrograms(): array
    {
        return $this->programs()->with(['department', 'campus'])->get()->map(fn ($p) => array_replace($p->toArray(), [
            'id' => (string) $p->id, 'code' => $p->slug, 'level' => $p->degree_level, 'department' => $p->department?->name ?? '', 'campus' => $p->campus?->name ?? '',
            'duration' => $p->duration_years ? $p->duration_years.' years' : '', 'study_mode' => '', 'accreditation' => $p->accreditation_council ?? '',
            'intake_seats' => $p->seat_capacity, 'tuition_semester' => $p->fee_per_semester ?? '', 'tuition_numeric' => (int) preg_replace('/\D/', '', $p->fee_per_semester ?? ''),
            'min_eligibility' => json_encode($p->eligibility_criteria ?? []), 'merit_formula' => '', 'published_at' => $p->created_at?->format('d M Y'),
        ], $this->metadata('program.'.$p->id)))->all();
    }

    public function storeProgram(array $data): void
    {
        $d = Department::withoutGlobalScopes()->where('university_id', $this->getTargetUniversity()->id)->where(fn ($q) => $q->where('id', is_numeric($data['department']) ? $data['department'] : 0)->orWhere('name', $data['department']))->firstOrFail();
        $p = Program::create(['university_id' => $d->university_id, 'campus_id' => $d->campus_id, 'department_id' => $d->id, 'slug' => Str::slug($data['code']).'-'.Str::lower(Str::random(6)), 'name' => $data['name'], 'degree_level' => strtolower($data['level']), 'discipline' => $data['discipline'] ?? 'unspecified', 'duration_years' => (int) $data['duration'], 'credit_hours' => $data['credit_hours'], 'fee_per_semester' => $data['tuition_semester'], 'accreditation_council' => $data['accreditation'], 'seat_capacity' => $data['intake_seats'], 'status' => 'active']);
        $this->saveMetadata('program.'.$p->id, Arr::only($data, ['code', 'study_mode', 'min_eligibility', 'merit_formula']));
    }

    public function updateProgram(string $id, array $data): void
    {
        $p = $this->programs()->findOrFail($id);
        $p->update(Arr::only($data, ['name', 'credit_hours', 'status']) + ['seat_capacity' => $data['intake_seats'], 'fee_per_semester' => $data['tuition_semester'], 'accreditation_council' => $data['accreditation'] ?? $p->accreditation_council]);
    }

    public function getFeeSchedules(): array
    {
        return ['academic_categories' => $this->programs()->get()->map(fn ($p) => ['id' => $p->id, 'category' => $p->name, 'tuition_per_semester' => (int) preg_replace('/\D/', '', $p->fee_per_semester ?? ''), 'tuition_annual' => $p->annual_fee ?? 'Not recorded', 'lab_charges' => 'Not recorded', 'admission_fee_onetime' => 'Not recorded', 'security_deposit_refundable' => 'Not recorded', 'updated_at' => $p->updated_at?->format('d M Y')])->all(), 'hostel_tariffs' => $this->metadata('hostel_tariffs'), 'policies' => array_replace(['installment_terms' => 'Not recorded', 'refund_policy' => 'Not recorded'], $this->metadata('fee_policies'))];
    }

    public function updateFees(array $data): void
    {
        $programs = $this->programs()->orderBy('id')->get();
        $program = $programs->get((int) $data['category_index']);
        abort_unless($program, 404);
        $program->update(['fee_per_semester' => 'PKR '.number_format($data['tuition']), 'annual_fee' => 'PKR '.number_format($data['tuition'] * 2)]);
    }

    public function getAdmissionCycles(): array
    {
        return Admission::withoutGlobalScopes()->where('university_id', $this->getTargetUniversity()->id)->get()->map(fn ($a) => [
            'id' => (string) $a->id, 'name' => $a->title, 'level' => implode(', ', $a->degree_levels ?? []), 'status' => $a->status, 'status_badge' => 'neutral', 'portal_open_date' => $a->registration_start ?? '', 'deadline_date' => $a->deadline_date?->toDateString() ?? '', 'entry_tests' => $a->test_dates ?? '', 'merit_list_1' => $a->merit_date ?? '', 'merit_list_2' => '', 'total_seats' => $this->programs()->sum('seat_capacity'), 'applications_received' => StudentApplication::where('university_id', $a->university_id)->count(), 'quotas' => [],
        ])->all();
    }

    public function updateAdmissionCycle(string $id, array $data): void
    {
        $a = Admission::withoutGlobalScopes()->where('university_id', $this->getTargetUniversity()->id)->findOrFail($id);
        $a->update(['deadline_date' => $data['deadline_date'], 'deadline' => $data['deadline_date'], 'status' => $data['status'], 'test_dates' => $data['entry_tests'] ?? $a->test_dates, 'merit_date' => $data['merit_list_1'] ?? $a->merit_date]);
    }

    public function getEligibilityRules(): array
    {
        return ['statutory_enforcements' => [], 'merit_weightage_formula' => array_replace(['net_test_weight' => 0, 'fsc_hssc_weight' => 0, 'matric_ssc_weight' => 0, 'sat_act_equivalent_allowed' => false, 'last_amended' => 'Not configured'], $this->metadata('merit_formula'))];
    }

    public function updateEligibilityRules(array $data): void
    {
        $this->saveMetadata('merit_formula', ['net_test_weight' => $data['net_weight'], 'fsc_hssc_weight' => $data['fsc_weight'], 'matric_ssc_weight' => $data['matric_weight'], 'last_amended' => now()->toDateTimeString()]);
    }

    public function getScholarships(): array
    {
        return Scholarship::withoutGlobalScopes()->where('university_id', $this->getTargetUniversity()->id)->get()->map(fn ($s) => [
            'id' => (string) $s->id, 'name' => $s->title, 'funding_agency' => $s->provider, 'category' => $s->category, 'coverage' => $s->coverage, 'annual_budget' => $this->metadata('scholarship.'.$s->id)['annual_budget'] ?? 'Not recorded', 'beneficiaries_target' => $s->beneficiaries, 'awarded_count' => null, 'eligibility_criteria' => implode('; ', $s->eligibility_criteria ?? []), 'application_window' => $s->deadline ?? '',
        ])->all();
    }

    public function storeScholarship(array $data): void
    {
        $u = $this->getTargetUniversity();
        $c = $this->campuses()->firstOrFail();
        $s = Scholarship::create(['university_id' => $u->id, 'campus_id' => $c->id, 'coverage_status' => 'verified', 'slug' => Str::slug($data['name']).'-'.Str::lower(Str::random(6)), 'title' => $data['name'], 'provider' => $data['funding_agency'], 'type' => Str::slug($data['category']), 'category' => $data['category'], 'coverage' => $data['coverage'], 'beneficiaries' => $data['beneficiaries_target'], 'eligibility_criteria' => [$data['eligibility_criteria']], 'deadline' => $data['application_window'] ?? null, 'status' => 'open']);
        $province = Province::where('slug', $c->province)->first();
        if ($province) {
            $s->regions()->sync([$province->id]);
        }
        $this->saveMetadata('scholarship.'.$s->id, ['annual_budget' => $data['annual_budget']]);
    }

    public function getDocuments(): array
    {
        $u = $this->getTargetUniversity();
        $applicants = StudentApplication::where('university_id', $u->id)->pluck('user_id');
        $submissions = [];
        foreach (StudentProfile::whereIn('user_id', $applicants)->get() as $profile) {
            foreach ($profile->documents_data ?? [] as $document) {
                $submissions[] = ['id' => $document['id'], 'applicant_name' => $profile->full_name, 'applicant_id' => $profile->applicant_id, 'program' => StudentApplication::where('user_id', $profile->user_id)->where('university_id', $u->id)->value('program_name') ?? '',
                    'doc_name' => $document['title'], 'file_name' => $document['file_name'], 'submitted_at' => $document['uploaded_at'], 'status' => $document['status'], 'status_label' => $document['status_label']];
            }
        }

        return ['candidate_submissions' => $submissions, 'institutional_documents' => KnowledgeBaseDocument::withoutGlobalScopes()->where('university_id', $u->id)->get()->map(fn ($d) => ['id' => $d->id, 'title' => $d->title, 'file_name' => basename($d->file_path ?? ''), 'file_size' => '', 'uploaded_at' => $d->created_at?->format('d M Y'), 'verification_badge' => $d->status])->all()];
    }

    public function verifyDocument(string $id, string $status): void
    {
        abort_unless(in_array($status, ['verified', 'rejected', 'pending'], true), 422);
        $u = $this->getTargetUniversity();
        if (ctype_digit($id)) {
            KnowledgeBaseDocument::withoutGlobalScopes()->where('university_id', $u->id)->findOrFail($id)->update(['status' => $status]);

            return;
        }
        $applicants = StudentApplication::where('university_id', $u->id)->pluck('user_id');
        foreach (StudentProfile::whereIn('user_id', $applicants)->get() as $profile) {
            $documents = $profile->documents_data ?? [];
            foreach ($documents as &$document) {
                if ($document['id'] === $id) {
                    $document['status'] = $status;
                    $document['status_label'] = ucfirst($status).' by institution';
                    $document['reviewed_by'] = auth()->id();
                    $document['reviewed_at'] = now()->toDateTimeString();
                    $profile->update(['documents_data' => $documents]);

                    return;
                }
            }
            unset($document);
        }
        abort(404);
    }

    public function getAiVerificationQueue(): array
    {
        return AiExtraction::with('reviewer')->where('institution_slug', $this->getTargetUniversity()->slug)->get()->map(fn ($e) => [
            'id' => (string) $e->id, 'entity' => $e->entity_type, 'field' => $e->field_name, 'field_key' => $e->field_name, 'previous_value' => $e->before_value, 'proposed_value' => $e->after_value, 'confidence_score' => $e->confidence_score, 'status' => $e->status, 'source_url' => $e->source_reference, 'source_title' => $e->source_reference, 'source_text_snippet' => '', 'extraction_timestamp' => $e->created_at?->toDateTimeString(), 'reviewed_at' => $e->reviewed_at?->toDateTimeString(), 'reviewed_by' => $e->reviewer?->name, 'validation_warning' => null, 'value_diff' => '',
        ])->all();
    }

    public function processAiVerification(string $id, string $action, ?string $editedValue = null): void
    {
        $e = AiExtraction::where('institution_slug', $this->getTargetUniversity()->slug)->findOrFail($id);
        // A review records a decision; unstructured extraction text cannot safely publish arbitrary fields.
        $e->update(['status' => $action, 'after_value' => $editedValue ?? $e->after_value, 'reviewed_by' => auth()->id(), 'reviewed_at' => now()]);
    }

    public function getInquiries(): array
    {
        return Inquiry::where('university_id', $this->getTargetUniversity()->id)->latest()->get()->map(fn ($i) => [
            'id' => (string) $i->id, 'candidate_name' => $i->name, 'candidate_email' => $i->email, 'candidate_phone' => $i->phone, 'qualification' => '', 'target_program' => '', 'subject' => $i->subject, 'message' => $i->message, 'status' => $i->status, 'priority' => $i->priority, 'received_at' => $i->created_at?->diffForHumans(), 'assigned_to' => '', 'replies' => $i->reply_content ? [['sender' => 'University administrator', 'timestamp' => $i->updated_at?->diffForHumans(), 'body' => $i->reply_content]] : [],
        ])->all();
    }

    public function replyInquiry(string $id, string $replyText): void
    {
        $i = Inquiry::where('university_id', $this->getTargetUniversity()->id)->findOrFail($id);
        $i->update(['reply_content' => $replyText, 'status' => 'resolved']);
        if ($i->user_id) {
            Notification::create(['user_id' => $i->user_id, 'title' => 'Your university inquiry has a reply', 'message' => $replyText, 'category' => 'general', 'dispatched_at' => now()]);
        }
    }

    public function updateInquiryStatus(string $id, string $status): void
    {
        abort_unless(in_array($status, ['new', 'in_progress', 'resolved'], true), 422);
        Inquiry::where('university_id', $this->getTargetUniversity()->id)->findOrFail($id)->update(['status' => $status]);
    }

    public function getStaffAndSettings(): array
    {
        return ['staff' => User::where('institution_slug', $this->getTargetUniversity()->slug)->get()->map(fn ($u) => ['name' => $u->name, 'email' => $u->email, 'role' => $u->role, 'role_badge' => '', 'department' => '', 'two_factor_enabled' => $u->two_factor_enabled])->all(), 'settings' => array_replace(['two_factor_required' => false, 'auto_ai_scrape_notification' => false, 'weekly_inquiry_digest' => false, 'notify_email' => $this->getTargetUniversity()->email ?? ''], $this->metadata('settings')), 'audit_log' => []];
    }

    public function updateSettings(array $data): void
    {
        $this->saveMetadata('settings', $data);
    }
}
