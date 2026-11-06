<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EligibilityRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'rule_key',
        'title',
        'authority',
        'statutory_reference',
        'category',
        'verified_date',
        'summary',
        'scope',
        'formula_note',
        'minimum_percentage',
        'parameters',
    ];

    protected function casts(): array
    {
        return [
            'minimum_percentage' => 'decimal:2',
            'parameters' => 'array',
        ];
    }
}
