<?php

namespace App\Models;

use App\Services\RegionCatalog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campus extends Model
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
        'campus_type', 'website', 'operational_status',
        'university_id',
        'slug',
        'name',
        'city',
        'province',
        'address',
        'is_main_campus',
        'established',
        'phone',
        'email',
        'status',
        'facilities',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'last_verified_at' => 'datetime',            'is_main_campus' => 'boolean',
            'established' => 'integer',
            'facilities' => 'array',
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

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function programs(): HasMany
    {
        return $this->hasMany(Program::class);
    }
}
