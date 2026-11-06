<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ScrapingJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_code',
        'data_source_id',
        'target',
        'url',
        'started_at',
        'finished_at',
        'duration',
        'pages_crawled',
        'records_extracted',
        'status',
        'http_code',
        'log_output',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
            'pages_crawled' => 'integer',
            'records_extracted' => 'integer',
            'http_code' => 'integer',
        ];
    }

    public function dataSource(): BelongsTo
    {
        return $this->belongsTo(DataSource::class);
    }

    public function extractions(): HasMany
    {
        return $this->hasMany(AiExtraction::class);
    }
}
