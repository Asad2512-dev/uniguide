<?php

namespace App\Models;

use App\Services\RegionCatalog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DataSource extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::addGlobalScope('regional_catalog', function (Builder $query): void {
            app(RegionCatalog::class)->apply($query, $query->getModel());
        });
    }

    protected $fillable = [
        'campus_id', 'coverage_status', 'university_id',
        'registry_key', 'source_url', 'source_type', 'fetched_at', 'verified_at', 'content_sha256',
        'name',
        'institution',
        'base_url',
        'endpoint',
        'feed_type',
        'reliability_score',
        'status',
        'http_status',
        'crawl_frequency',
        'last_crawled_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'reliability_score' => 'decimal:2',
            'http_status' => 'integer',
            'last_crawled_at' => 'datetime',
            'fetched_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scrapingJobs(): HasMany
    {
        return $this->hasMany(ScrapingJob::class);
    }

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }
}
