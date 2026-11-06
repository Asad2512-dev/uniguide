<?php

namespace App\Models;

use App\Services\RegionCatalog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admission extends Model
{
    use HasFactory, SoftDeletes;

    protected static function booted(): void
    {
        static::addGlobalScope('regional_catalog', function (Builder $query): void {
            app(RegionCatalog::class)->apply($query, $query->getModel());
        });
    }

    protected $fillable = [
        'campus_id', 'coverage_status',
        'university_id',
        'slug',
        'title',
        'session',
        'status',
        'degree_levels',
        'disciplines',
        'campuses',
        'registration_start',
        'deadline',
        'deadline_date',
        'test_dates',
        'merit_date',
        'classes_date',
        'entry_test_requirement',
        'application_fee',
        'official_url',
        'source_citation',
        'verified_at',
        'featured',
        'summary',
        'eligibility_summary',
        'weightage_formula',
        'available_programs',
    ];

    protected function casts(): array
    {
        return [
            'deadline_date' => 'date',
            'featured' => 'boolean',
            'degree_levels' => 'array',
            'disciplines' => 'array',
            'campuses' => 'array',
            'available_programs' => 'array',
        ];
    }

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->whereIn('status', ['open', 'closing_soon']);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
                ->orWhere('session', 'like', "%{$term}%")
                ->orWhere('summary', 'like', "%{$term}%");
        });
    }

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }
}
