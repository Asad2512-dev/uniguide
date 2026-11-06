<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'queue_code',
        'institution_slug',
        'institution_name',
        'entity_type',
        'field_name',
        'before_value',
        'after_value',
        'priority',
        'status',
        'submitted_by',
        'submitted_at_text',
        'source_doc',
        'confidence',
    ];

    protected function casts(): array
    {
        return [
            'confidence' => 'decimal:2',
        ];
    }
}
