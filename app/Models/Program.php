<?php

namespace App\Models;

use App\Services\RegionCatalog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Program extends Model
{
    use HasFactory, SoftDeletes;

    protected static function booted(): void
    {
        static::addGlobalScope('regional_catalog', function (Builder $query): void {
            app(RegionCatalog::class)->apply($query, $query->getModel());
        });
    }

    protected $fillable = [
        'university_id',
        'campus_id',
        'department_id',
        'slug',
        'name',
        'degree_level',
        'degree_title',
        'discipline',
        'duration_years',
        'total_semesters',
        'credit_hours',
        'fee_per_semester',
        'annual_fee',
        'cutoff_merit',
        'estimated_closing_merit',
        'accreditation_council',
        'accreditation_status',
        'seat_capacity',
        'eligibility_criteria',
        'description',
        'curriculum_summary',
        'career_prospects',
        'featured',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'duration_years' => 'integer',
            'total_semesters' => 'integer',
            'credit_hours' => 'integer',
            'seat_capacity' => 'integer',
            'featured' => 'boolean',
            'eligibility_criteria' => 'array',
        ];
    }

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function studentApplications(): HasMany
    {
        return $this->hasMany(StudentApplication::class);
    }

    public function scopeByDegreeLevel(Builder $query, ?string $level): Builder
    {
        if (! $level || $level === 'all') {
            return $query;
        }

        return $query->where('degree_level', $level);
    }

    public function scopeByDiscipline(Builder $query, ?string $discipline): Builder
    {
        if (! $discipline || $discipline === 'all') {
            return $query;
        }

        return $query->where('discipline', $discipline);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('discipline', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%");
        });
    }
}
