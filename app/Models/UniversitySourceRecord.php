<?php

namespace App\Models;

use App\Services\RegionCatalog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UniversitySourceRecord extends Model
{
    protected static function booted(): void
    {
        static::addGlobalScope('regional_catalog', function (Builder $query): void {
            app(RegionCatalog::class)->apply($query, $query->getModel());
        });
    }

    protected $fillable = ['university_id', 'campus_id', 'data_source_id', 'evidence_key', 'evidence_type', 'verification_status', 'evidence'];

    protected function casts(): array
    {
        return ['evidence' => 'array'];
    }

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }

    public function dataSource(): BelongsTo
    {
        return $this->belongsTo(DataSource::class)->withoutGlobalScope('regional_catalog');
    }
}
