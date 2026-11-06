<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'university_id',
        'program_id',
        'university_name',
        'university_acronym',
        'university_slug',
        'program_name',
        'campus',
        'stage',
        'stage_label',
        'application_ref',
        'deadline',
        'deadline_date',
        'fee_challan_status',
        'test_date',
        'calculated_aggregate',
        'closing_cutoff_ref',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'deadline_date' => 'date',
            'calculated_aggregate' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }
}
