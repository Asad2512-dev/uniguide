<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UniversityApplication extends Model
{
    protected $fillable = ['reference', 'name', 'website', 'city', 'province', 'sector', 'contact_name', 'email', 'phone', 'description', 'status', 'university_id', 'reviewed_by', 'reviewed_at'];

    protected function casts(): array
    {
        return ['reviewed_at' => 'datetime'];
    }
}
