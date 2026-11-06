<?php

namespace App\Models;

use App\Services\RegionCatalog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class University extends Model
{
    use HasFactory, SoftDeletes;

    protected static function booted(): void
    {
        static::addGlobalScope('regional_catalog', function (Builder $query): void {
            app(RegionCatalog::class)->apply($query, $query->getModel());
        });
    }

    protected $fillable = [
        'registry_key', 'city_id', 'recognition_status', 'verification_status', 'last_verified_at',
        'institution_status', 'campus_coverage',
        'slug',
        'name',
        'acronym',
        'charter_type',
        'sector',
        'hec_ranking',
        'hec_category',
        'established',
        'charter_citation',
        'gazette_reference',
        'website',
        'phone',
        'email',
        'address',
        'province',
        'province_label',
        'city',
        'fee_range',
        'fee_min',
        'fee_max',
        'acceptance_rate',
        'total_students',
        'faculty_phd_ratio',
        'campus_size',
        'hostel_availability',
        'status',
        'deadline',
        'featured',
        'accreditations',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'last_verified_at' => 'datetime',            'established' => 'integer',
            'fee_min' => 'integer',
            'fee_max' => 'integer',
            'featured' => 'boolean',
            'accreditations' => 'array',
        ];
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function sourceRecords(): HasMany
    {
        return $this->hasMany(UniversitySourceRecord::class);
    }

    public function campuses(): HasMany
    {
        return $this->hasMany(Campus::class);
    }

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    public function programs(): HasMany
    {
        return $this->hasMany(Program::class);
    }

    public function admissions(): HasMany
    {
        return $this->hasMany(Admission::class);
    }

    public function scholarships(): HasMany
    {
        return $this->hasMany(Scholarship::class);
    }

    public function administrators(): HasMany
    {
        return $this->hasMany(User::class, 'institution_slug', 'slug');
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('acronym', 'like', "%{$term}%")
                ->orWhere('city', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%")
                ->orWhereHas('campuses', fn (Builder $campuses): Builder => $campuses->where('name', 'like', "%{$term}%")->orWhere('city', 'like', "%{$term}%"));
        });
    }

    public function scopeByProvince(Builder $query, ?string $province): Builder
    {
        if (! $province || $province === 'all') {
            return $query;
        }

        return $query->where('province', $province);
    }

    public function scopeBySector(Builder $query, ?string $sector): Builder
    {
        if (! $sector || $sector === 'all') {
            return $query;
        }

        return $query->where('sector', $sector);
    }
}
