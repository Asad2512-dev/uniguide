<?php

namespace App\Models;

use App\Services\RegionCatalog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::addGlobalScope('regional_catalog', function (Builder $query): void {
            app(RegionCatalog::class)->apply($query, $query->getModel());
        });
    }

    protected $fillable = [
        'university_id',
        'campus_id',
        'name',
        'faculty_name',
        'head_of_department',
        'dean_name',
        'email',
        'status',
    ];

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }

    public function programs(): HasMany
    {
        return $this->hasMany(Program::class);
    }
}
