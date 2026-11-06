<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CharterApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'approval_code',
        'institution_name',
        'institution_slug',
        'application_type',
        'applicant_rector',
        'details',
        'inspection_committee',
        'submission_date',
        'status',
        'status_label',
        'ratified_at',
    ];

    protected function casts(): array
    {
        return [
            'ratified_at' => 'datetime',
        ];
    }
}
