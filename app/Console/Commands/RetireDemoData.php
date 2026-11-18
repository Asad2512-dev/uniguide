<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class RetireDemoData extends Command
{
    protected $signature = 'uniguide:retire-demo-data';

    protected $description = 'Back up existing records, deactivate extra accounts, and retire known sample records.';

    public function handle(): int
    {
        $backup = [];
        foreach (Schema::getTables(DB::getDriverName() === 'sqlite' ? 'main' : DB::getDatabaseName()) as $table) {
            $name = $table['name'];
            $backup[$name] = DB::table($name)->get()->toArray();
        }
        $path = 'backups/before-demo-retirement-'.now()->format('Ymd-His').'.json';
        Storage::disk('local')->put($path, json_encode($backup, JSON_THROW_ON_ERROR));
        DB::transaction(function () {
            DB::table('users')->whereNotIn('email', ['numl@gmail.com', 'student@gmail.com', 'admin@gmail.com'])->update(['status' => 'inactive']);
            $legacyIds = DB::table('users')->whereIn('email', ['ahmad.raza.khan@student.pk', 'ahmad.raza@student.uniguide.pk'])->pluck('id');
            foreach (['notifications', 'saved_items', 'student_applications'] as $table) {
                DB::table($table)->whereIn('user_id', $legacyIds)->delete();
            }
            DB::table('ai_extractions')->whereIn('extraction_code', ['ext-1', 'ext-2', 'ext-3', 'ext-4'])->delete();
            DB::table('verification_requests')->whereIn('queue_code', ['vq-1', 'vq-2', 'vq-3', 'vq-4'])->delete();
            DB::table('charter_approvals')->whereIn('approval_code', ['appr-1', 'appr-2', 'appr-3'])->delete();
            DB::table('knowledge_base_documents')->whereIn('document_code', ['kb-doc-1', 'kb-doc-2', 'kb-doc-3', 'kb-doc-4'])->delete();
            DB::table('scraping_jobs')->whereIn('job_code', ['JOB-CRW-9842', 'JOB-CRW-9841', 'JOB-CRW-9840', 'JOB-CRW-9839', 'SJ-661DB6'])->delete();
            DB::table('audit_logs')->whereIn('action', ['Endorsed NUST Fall 2026 audited fee gazette', 'Indexed 48 pages from NUST admissions portal', 'Filed inspection report for FAST Multan campus', 'Verified HEC SRO 45(I)/2026 remedial math ruleset', 'Automated Web Crawl executed for FAST-NUCES [SJ-661DB6]'])->delete();
            DB::table('data_sources')->whereNull('registry_key')->whereIn('name', ['NUST Official Admissions Portal & Prospectus Feed', 'FAST-NUCES Central University Portal', 'Pakistan Engineering Council (PEC) Gazette Repository', 'LUMS Admissions & Financial Aid Feed', 'UET Lahore Admission Portal'])->delete();
            DB::table('eligibility_rules')->whereIn('rule_key', ['pec-eng-60', 'hec-premed-cs', 'nceac-cs-50', 'ibcc-alevels', 'nust-net-formula', 'fast-nu-formula', 'comsats-nat-formula', 'uet-ecat-formula'])->delete();
            DB::table('programs')->whereIn('slug', ['nust-bs-software-engineering', 'nust-bs-computer-science', 'fast-bs-computer-science', 'fast-bs-artificial-intelligence', 'giki-bs-artificial-intelligence', 'lums-bsc-accounting-finance', 'iba-bba-hons', 'aku-mbbs', 'uet-be-electrical-engineering'])->where('created_at', '2026-11-04 18:58:10')->update(['deleted_at' => now()]);
            DB::table('admissions')->whereIn('slug', ['nust-fall-2026', 'fast-nuces-fall-2026', 'lums-fall-2026', 'giki-fall-2026', 'iba-karachi-fall-2026', 'uet-lahore-fall-2026'])->update(['deleted_at' => now()]);
            DB::table('scholarships')->whereIn('slug', ['hec-need-based', 'ehsaas-undergraduate', 'lums-nop', 'peef-special-quota', 'beef-excellence-scholarship', 'iba-sindh-talent-hunt', 'nust-endowment-aid', 'fast-study-loan'])->update(['deleted_at' => now()]);
            $legacyUniversities = DB::table('universities')->whereNull('registry_key')->whereIn('slug', ['nust', 'fast-nuces', 'lums', 'giki', 'iba-karachi', 'aku', 'uet-lahore'])->pluck('id');
            DB::table('campuses')->whereNull('registry_key')->whereIn('university_id', $legacyUniversities)->update(['deleted_at' => now()]);
            DB::table('universities')->whereIn('id', $legacyUniversities)->update(['deleted_at' => now()]);
        });
        $this->info('Backup saved privately: '.$path);
        $this->info('Three requested logins remain active; known sample records retired.');

        return self::SUCCESS;
    }
}
