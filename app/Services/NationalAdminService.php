<?php

namespace App\Services;

use App\Jobs\ProcessWebScrapeJob;
use App\Models\AiExtraction;
use App\Models\AuditLog;
use App\Models\Campus;
use App\Models\DataSource;
use App\Models\KnowledgeBaseChunk;
use App\Models\KnowledgeBaseDocument;
use App\Models\Notification;
use App\Models\Program;
use App\Models\ScrapingJob;
use App\Models\StudentApplication;
use App\Models\SystemSetting;
use App\Models\University;
use App\Models\User;
use App\Models\VerificationRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class NationalAdminService
{
    public function getDashboardData(): array
    {
        return ['kpis' => [], 'regional_breakdown' => [], 'system_alerts' => [], 'recent_changes' => []];
    }

    public function getUniversities(): array
    {
        return University::withCount(['campuses', 'programs'])->orderBy('name')->get()->map(fn ($u) => [
            'id' => $u->id, 'name' => $u->name, 'acronym' => $u->acronym ?? '', 'slug' => $u->slug, 'sector' => ucfirst($u->sector), 'charter_type' => $u->charter_type ?? 'Not recorded',
            'province' => $u->province_label ?? $u->province, 'active_campuses_count' => $u->campuses_count, 'active_programs_count' => $u->programs_count, 'last_audit_date' => $u->last_verified_at?->format('d M Y') ?? 'Not reviewed', 'status' => $u->verification_status ?? 'pending',
        ])->all();
    }

    public function getApprovals(): array
    {
        return [];
    }

    public function processApproval(string $id, string $action): void
    {
        abort(410, 'Use university listing applications.');
    }

    public function getProgramsAndCampuses(): array
    {
        return ['total_programs' => Program::count(), 'total_campuses' => Campus::count(), 'programs' => Program::with(['university', 'campus'])->get()->map(fn ($p) => [
            'id' => $p->id, 'university' => $p->university?->name ?? '', 'campus' => $p->campus?->name ?? '', 'name' => $p->name, 'level' => $p->degree_level, 'council' => $p->accreditation_council ?? 'Not recorded', 'quota_seats' => $p->seat_capacity, 'annual_fee' => $p->annual_fee ?? 'Not recorded', 'status' => $p->status,
        ])->all()];
    }

    public function getDataSources(): array
    {
        return DataSource::withoutGlobalScopes()->latest()->get()->map(fn ($s) => array_replace($s->toArray(), ['last_crawled' => $s->last_crawled_at?->diffForHumans() ?? 'Never collected']))->all();
    }

    public function createDataSource(array $data): void
    {
        $data['created_by'] = auth()->id();
        $data['status'] = 'pending';
        $data['http_status'] = 0;
        $data['reliability_score'] = 0;
        DataSource::create(Arr::only($data, (new DataSource)->getFillable()));
    }

    public function getScrapingControl(): array
    {
        $total = ScrapingJob::count();
        $success = ScrapingJob::where('status', 'completed')->count();

        return ['stats' => ['total_sources' => DataSource::withoutGlobalScopes()->count(), 'active_crawlers' => ScrapingJob::where('status', 'running')->count(), 'queue_depth' => ScrapingJob::where('status', 'queued')->count(), 'failed_today' => ScrapingJob::where('status', 'failed')->whereDate('created_at', today())->count(), 'success_rate' => $total ? round($success / $total * 100, 1) : 0, 'avg_crawl_time' => 'Not measured'],
            'crawlers' => ScrapingJob::latest()->limit(50)->get()->map(fn ($j) => ['id' => $j->id, 'source_name' => $j->target, 'status' => $j->status, 'status_label' => str_replace('_', ' ', $j->status), 'badge_color' => 'neutral', 'last_success' => $j->finished_at?->diffForHumans() ?? 'Not completed', 'next_scheduled' => 'Not scheduled', 'freshness' => 'Not measured', 'pages_crawled' => $j->pages_crawled, 'entities_found' => $j->records_extracted, 'error_count' => $j->status === 'failed' ? 1 : 0])->all()];
    }

    public function triggerCrawl(string $sourceName): void
    {
        DataSource::withoutGlobalScopes()->where('name', $sourceName)->firstOrFail();
        ProcessWebScrapeJob::dispatch($sourceName);
    }

    public function retryFailedCrawls(): void
    {
        ScrapingJob::where('status', 'failed')->whereNotNull('data_source_id')->get()->each(function ($job) {
            if ($source = DataSource::withoutGlobalScopes()->find($job->data_source_id)) {
                ProcessWebScrapeJob::dispatch($source->name);
            }
        });
    }

    public function getScrapingHistory(): array
    {
        return ScrapingJob::latest()->limit(100)->get()->map(fn ($j) => ['job_id' => $j->job_code, 'target' => $j->target, 'url' => $j->url, 'started_at' => $j->started_at?->toDateTimeString(), 'duration' => $j->duration ?? 'Not measured', 'pages' => $j->pages_crawled, 'records_extracted' => $j->records_extracted, 'status' => $j->status, 'http_code' => $j->http_code, 'log_output' => $j->log_output])->all();
    }

    public function getAiExtractions(): array
    {
        return AiExtraction::latest()->get()->map(fn ($e) => ['id' => (string) $e->id, 'university' => $e->university_name, 'entity' => $e->entity_type, 'field_name' => $e->field_name, 'before_value' => $e->before_value, 'after_value' => $e->after_value, 'confidence' => $e->confidence_score, 'status' => $e->status, 'timestamp' => $e->created_at?->toDateTimeString(), 'source_document' => $e->source_reference, 'extracted_snippet' => '', 'validation_status' => 'Requires review', 'has_warning' => true])->all();
    }

    public function processExtraction(string $id, string $decision, ?string $editedValue = null): void
    {
        abort_unless(in_array($decision, ['approved', 'rejected', 'edited'], true), 422);
        $e = AiExtraction::findOrFail($id);
        $e->update(['status' => $decision, 'after_value' => $editedValue ?? $e->after_value, 'reviewed_by' => auth()->id(), 'reviewed_at' => now()]);
        $this->audit('Recorded extraction review '.$e->id.': '.$decision);
    }

    public function getVerificationQueue(): array
    {
        return VerificationRequest::latest()->get()->map(fn ($v) => array_replace($v->toArray(), ['id' => (string) $v->id, 'institution' => $v->institution_name, 'field' => $v->field_name, 'submitted_at' => $v->submitted_at_text ?? $v->created_at?->toDateTimeString()]))->all();
    }

    public function processVerification(string $id, string $action): void
    {
        abort_unless(in_array($action, ['approved', 'rejected', 'flagged'], true), 422);
        VerificationRequest::findOrFail($id)->update(['status' => $action]);
        $this->audit('Recorded verification review '.$id.': '.$action);
    }

    public function getKnowledgeBase(): array
    {
        $documents = KnowledgeBaseDocument::withoutGlobalScopes()->withCount('chunks')->get();

        return ['stats' => ['total_documents' => $documents->count(), 'embedded_chunks' => KnowledgeBaseChunk::withoutGlobalScopes()->count(), 'embedding_model' => 'Not configured', 'index_status' => 'Local source records', 'search_latency' => 'Not measured', 'failed_chunks' => 0],
            'documents' => $documents->map(fn ($d) => ['id' => $d->id, 'title' => $d->title, 'authority' => $d->authority, 'chunks_count' => $d->chunks_count, 'status' => $d->status, 'status_color' => 'neutral', 'last_embedded' => $d->last_embedded_at?->toDateTimeString() ?? 'Not indexed'])->all()];
    }

    public function reindexKnowledgeBase(): void
    {
        throw ValidationException::withMessages(['knowledge_base' => 'No embedding service is configured. No indexing was performed.']);
    }

    public function getChatbotConfig(): array
    {
        return array_replace(['model' => 'registry', 'temperature' => 0, 'max_tokens' => 2048, 'citation_threshold' => 1, 'inject_pec_rules' => false, 'inject_hec_premed_rules' => false, 'inject_ibcc_rules' => false, 'enforce_strict_citations' => true, 'guardrails_hallucination_block' => true, 'system_prompt' => 'Use only recorded university data. Explain missing information.'], SystemSetting::get('counselor.config', []));
    }

    public function updateChatbotConfig(array $data): void
    {
        if (($data['model'] ?? 'registry') !== 'registry') {
            throw ValidationException::withMessages(['model' => 'Only database registry mode is connected.']);
        }
        SystemSetting::set('counselor.config', $data);
        $this->audit('Updated counselor configuration');
    }

    public function getUsers(): array
    {
        return User::where('status', 'active')->latest()->get()->map(fn ($u) => ['id' => $u->id, 'name' => $u->name, 'email' => $u->email, 'role' => match ($u->role) {
            'student' => 'Applicant Candidate','university_admin','university_staff' => 'University Administrator',default => 'Platform Administrator'
        }, 'scope' => $u->institution_slug ?? 'Platform', 'status' => ucfirst($u->status), 'two_factor' => $u->two_factor_enabled ? 'Enabled' : 'Disabled', 'created_at' => $u->created_at?->format('d M Y')])->all();
    }

    public function inviteUser(array $data): void
    {
        throw ValidationException::withMessages(['email' => 'Email invitations are not configured. No invitation was sent. University listing requests can be submitted through the joining form.']);
    }

    public function getNotifications(): array
    {
        return Notification::latest()->get()->map(fn ($n) => array_replace($n->toArray(), ['channels' => $n->channels ?? ['in_app'], 'dispatched_at' => $n->dispatched_at?->toDateTimeString() ?? 'Not sent']))->all();
    }

    public function createNotification(array $data): void
    {
        $audience = strtolower($data['target_audience']);
        $query = User::where('status', 'active');
        if (str_contains($audience, 'student') || str_contains($audience, 'candidate')) {
            $query->where('role', 'student');
        } elseif (str_contains($audience, 'universit')) {
            $query->whereIn('role', ['university_admin', 'university_staff']);
        } elseif (! in_array($audience, ['all', 'all users', 'everyone'], true)) {
            throw ValidationException::withMessages(['target_audience' => 'Choose students, universities, or all users.']);
        }
        DB::transaction(function () use ($query, $data) {
            $query->get()->each(fn ($u) => Notification::create(['user_id' => $u->id, 'title' => $data['title'], 'message' => $data['message'] ?? '', 'category' => $data['category'], 'target_audience' => $data['target_audience'], 'channels' => ['in_app'], 'dispatched_at' => now(), 'status' => 'active']));
        });
    }

    public function getAnalytics(): array
    {
        $total = StudentApplication::count();
        $demand = Program::withCount('studentApplications')->get()->groupBy('discipline')->map(fn ($items, $name) => ['name' => $name, 'share' => $total ? round($items->sum('student_applications_count') / $total * 100, 1) : 0, 'candidates' => $items->sum('student_applications_count')])->values()->all();

        return ['discipline_demand' => $demand, 'provincial_inflow' => [], 'fee_inflation_index' => []];
    }

    public function getSettingsAndAuditLogs(): array
    {
        return ['settings' => array_replace(['crawler_concurrency' => 1, 'api_rate_limit_per_minute' => 60, 'require_hec_commissioner_approval_for_fee_hike' => false, 'immutable_audit_logging_enabled' => true], SystemSetting::get('platform.settings', [])),
            'audit_logs' => AuditLog::latest()->limit(100)->get()->map(fn ($a) => ['timestamp' => $a->created_at?->toDateTimeString(), 'user' => $a->user_name, 'role' => $a->user_role, 'action' => $a->action, 'ip' => $a->ip_address])->all()];
    }

    public function updateSettings(array $data): void
    {
        SystemSetting::set('platform.settings', array_replace($this->getSettingsAndAuditLogs()['settings'], $data));
        $this->audit('Updated platform settings');
    }

    private function audit(string $action): void
    {
        AuditLog::create(['user_id' => auth()->id(), 'user_name' => auth()->user()?->name ?? 'System', 'user_role' => auth()->user()?->role ?? 'system', 'action' => $action, 'ip_address' => request()->ip()]);
    }
}
