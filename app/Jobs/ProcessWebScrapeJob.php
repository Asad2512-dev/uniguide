<?php

namespace App\Jobs;

use App\Models\AuditLog;
use App\Models\DataSource;
use App\Models\ScrapingJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class ProcessWebScrapeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $sourceName,
        public ?string $targetUrl = null
    ) {}

    public function handle(): void
    {
        $source = DataSource::withoutGlobalScope('regional_catalog')
            ->where('name', $this->sourceName)
            ->when($this->targetUrl, fn ($query) => $query->where(function ($urls): void {
                $urls->where('source_url', $this->targetUrl)->orWhere('base_url', $this->targetUrl);
            }))->first();
        $allowed = $source !== null && DataSource::query()->whereKey($source->id)->exists();
        $url = $source?->source_url ?? $source?->base_url ?? '';
        $validUrl = filter_var($url, FILTER_VALIDATE_URL)
            && in_array(parse_url($url, PHP_URL_SCHEME), ['http', 'https'], true)
            && ! parse_url($url, PHP_URL_USER) && ! parse_url($url, PHP_URL_PASS);
        $allowed = $allowed && $validUrl;
        $status = $allowed ? 'awaiting_manual_review' : 'blocked_out_of_coverage';
        $reason = $allowed
            ? 'The campus source is within release coverage. No live crawler or verified extraction pipeline is configured; no pages were fetched and no data was published.'
            : 'Collection blocked: source must have an explicitly verified, permitted campus association. Unresolved and mixed-campus sources require manual review.';
        $code = 'SJ-'.Str::upper(Str::random(12));

        ScrapingJob::create([
            'job_code' => $code,
            'data_source_id' => $source?->id,
            'target' => $this->sourceName,
            'url' => $validUrl ? $url : '',
            'started_at' => now(),
            'finished_at' => now(),
            'duration' => null,
            'pages_crawled' => 0,
            'records_extracted' => 0,
            'status' => $status,
            'http_code' => 0,
            'log_output' => $reason,
        ]);

        AuditLog::create([
            'user_name' => 'Regional collection worker',
            'user_role' => 'System',
            'action' => $status.' for '.$this->sourceName.' ['.$code.']: '.$reason,
            'ip_address' => '127.0.0.1',
        ]);
    }
}
