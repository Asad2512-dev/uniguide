<?php

namespace App\Models;

use App\Services\RegionCatalog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Scholarship extends Model
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
        'provider',
        'type',
        'type_label',
        'category',
        'funding_type',
        'coverage',
        'coverage_amount',
        'deadline',
        'deadline_date',
        'beneficiaries',
        'applicable_universities',
        'universities_scope',
        'target_provinces',
        'degree_levels',
        'gpa_requirement',
        'income_ceiling',
        'description',
        'eligibility_criteria',
        'benefits',
        'selection_process',
        'application_process',
        'required_documents',
        'official_url',
        'source_citation',
        'verified_at',
        'status',
        'featured',
    ];

    protected function casts(): array
    {
        return [
            'deadline_date' => 'date',
            'income_ceiling' => 'integer',
            'featured' => 'boolean',
            'target_provinces' => 'array',
            'degree_levels' => 'array',
            'eligibility_criteria' => 'array',
            'benefits' => 'array',
            'selection_process' => 'array',
            'required_documents' => 'array',
        ];
    }

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function scopeByType(Builder $query, ?string $type): Builder
    {
        if (! $type || $type === 'all') {
            return $query;
        }

        return $query->where('type', $type);
    }

    public function scopeByCategory(Builder $query, ?string $category): Builder
    {
        if (! $category || $category === 'all') {
            return $query;
        }

        return $query->where('category', $category);
    }

    public function scopeByStatus(Builder $query, ?string $status): Builder
    {
        if (! $status || $status === 'all') {
            return $query;
        }

        return $query->where('status', $status);
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('status', 'open');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }

    public function regions(): BelongsToMany
    {
        return $this->belongsToMany(Province::class, 'scholarship_regions');
    }
}
