<?php

namespace Database\Seeders;

use App\Models\AiExtraction;
use App\Models\AuditLog;
use App\Models\CharterApproval;
use App\Models\DataSource;
use App\Models\KnowledgeBaseDocument;
use App\Models\ScrapingJob;
use App\Models\SystemSetting;
use App\Models\VerificationRequest;
use App\Services\NationalAdminService;
use Illuminate\Database\Seeder;

class NationalAdminSeeder extends Seeder
{
    public function run(): void
    {
        $service = app(NationalAdminService::class);

        // 1. Data Sources
        $sources = $service->getDataSources();
        foreach ($sources as $src) {
            DataSource::withoutGlobalScope('regional_catalog')->updateOrCreate(
                ['name' => $src['name']],
                [
                    'institution' => $src['institution'],
                    'base_url' => $src['base_url'],
                    'endpoint' => $src['endpoint'] ?? '/',
                    'feed_type' => $src['feed_type'] ?? 'HTML Table Scraper',
                    'reliability_score' => $src['reliability_score'] ?? 99.0,
                    'status' => $src['status'] ?? 'healthy',
                    'http_status' => $src['http_status'] ?? 200,
                    'crawl_frequency' => $src['crawl_frequency'] ?? 'Daily',
                    'last_crawled_at' => now()->subHours(3),
                ]
            );
        }

        // 2. Scraping Jobs
        $jobs = $service->getScrapingHistory();
        foreach ($jobs as $j) {
            ScrapingJob::updateOrCreate(
                ['job_code' => $j['job_id']],
                [
                    'target' => $j['target'],
                    'url' => $j['url'],
                    'started_at' => now()->subHours(4),
                    'finished_at' => now()->subHours(4)->addSeconds(4),
                    'duration' => $j['duration'] ?? '3.5s',
                    'pages_crawled' => $j['pages'] ?? 12,
                    'records_extracted' => $j['records_extracted'] ?? 6,
                    'status' => strtolower($j['status'] ?? 'completed'),
                    'http_code' => $j['http_code'] ?? 200,
                    'log_output' => $j['log_snippet'] ?? null,
                ]
            );
        }

        // 3. AI Extractions
        $extractions = $service->getAiExtractions();
        foreach ($extractions as $ext) {
            AiExtraction::updateOrCreate(
                ['extraction_code' => $ext['id']],
                [
                    'institution_slug' => $ext['slug'] ?? 'nust',
                    'university_name' => $ext['university'] ?? 'NUST Islamabad',
                    'entity_type' => $ext['entity'] ?? 'Program Tuition',
                    'field_name' => $ext['field_name'] ?? 'Fee Revision',
                    'before_value' => $ext['before_value'] ?? null,
                    'after_value' => $ext['after_value'] ?? null,
                    'confidence_score' => $ext['confidence'] ?? 98.0,
                    'status' => $ext['status'] ?? 'pending',
                    'source_reference' => $ext['source_reference'] ?? 'Prospectus Gazette',
                ]
            );
        }

        // 4. Verification Queue
        $queue = $service->getVerificationQueue();
        foreach ($queue as $item) {
            VerificationRequest::updateOrCreate(
                ['queue_code' => $item['id']],
                [
                    'institution_slug' => 'nust',
                    'institution_name' => $item['institution'],
                    'entity_type' => $item['entity_type'],
                    'field_name' => $item['field'] ?? ($item['field_name'] ?? 'General Specification'),
                    'before_value' => $item['before_value'] ?? null,
                    'after_value' => $item['after_value'] ?? null,
                    'priority' => $item['priority'] ?? 'Medium',
                    'status' => $item['status'] ?? 'Pending Review',
                    'submitted_by' => $item['submitted_by'] ?? 'Registrar',
                    'submitted_at_text' => $item['submitted_at'] ?? 'Today',
                    'source_doc' => $item['source_doc'] ?? 'Gazette SRO',
                    'confidence' => $item['confidence'] ?? 98.5,
                ]
            );
        }

        // 5. Charter Approvals
        $approvals = $service->getApprovals();
        foreach ($approvals as $app) {
            CharterApproval::updateOrCreate(
                ['approval_code' => $app['id']],
                [
                    'institution_name' => $app['institution'],
                    'application_type' => $app['application_type'],
                    'applicant_rector' => $app['applicant_rector'] ?? null,
                    'details' => $app['details'],
                    'inspection_committee' => $app['inspection_committee'] ?? null,
                    'submission_date' => $app['submission_date'] ?? '14 April 2026',
                    'status' => $app['status'] ?? 'pending_review',
                    'status_label' => $app['status_label'] ?? null,
                ]
            );
        }

        // 6. Knowledge Base Documents
        $kb = $service->getKnowledgeBase();
        if (! empty($kb['documents'])) {
            foreach ($kb['documents'] as $doc) {
                KnowledgeBaseDocument::withoutGlobalScope('regional_catalog')->updateOrCreate(
                    ['document_code' => $doc['id']],
                    [
                        'title' => $doc['title'],
                        'authority' => $doc['authority'],
                        'statutory_reference' => $doc['title'],
                        'category' => 'statutory',
                        'chunks_count' => $doc['chunks_count'] ?? 100,
                        'status' => $doc['status'] ?? 'Indexed',
                        'last_embedded_at' => now()->subDays(10),
                    ]
                );
            }
        }

        // 7. System Settings
        $settingsData = $service->getSettingsAndAuditLogs();
        if (! empty($settingsData['settings'])) {
            foreach ($settingsData['settings'] as $k => $v) {
                SystemSetting::set($k, $v);
            }
        }

        // 8. Audit Logs
        if (! empty($settingsData['audit_logs'])) {
            foreach ($settingsData['audit_logs'] as $log) {
                AuditLog::create([
                    'user_name' => $log['user'],
                    'user_role' => $log['role'],
                    'action' => $log['action'],
                    'ip_address' => $log['ip'] ?? '127.0.0.1',
                    'created_at' => now()->subHours(rand(1, 48)),
                ]);
            }
        }
    }
}
