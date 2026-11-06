<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'applicant_id',
        'full_name',
        'phone',
        'city',
        'domicile_province',
        'domicile_label',
        'cnic',
        'gender',
        'dob',
        'stream',
        'stream_label',
        'monthly_household_income',
        'matric_data',
        'fsc_data',
        'entry_tests_data',
        'preferences',
        'onboarding_completed',
    ];

    protected function casts(): array
    {
        return [
            'dob' => 'date',
            'monthly_household_income' => 'integer',
            'onboarding_completed' => 'boolean',
            'matric_data' => 'array',
            'fsc_data' => 'array',
            'entry_tests_data' => 'array',
            'preferences' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
