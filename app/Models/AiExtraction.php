<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiExtraction extends Model
{
    use HasFactory;

    protected $fillable = [
        'extraction_code',
        'scraping_job_id',
        'institution_slug',
        'university_name',
        'entity_type',
        'field_name',
        'before_value',
        'after_value',
        'confidence_score',
        'status',
        'source_reference',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'confidence_score' => 'decimal:2',
            'reviewed_at' => 'datetime',
        ];
    }

    public function scrapingJob(): BelongsTo
    {
        return $this->belongsTo(ScrapingJob::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
