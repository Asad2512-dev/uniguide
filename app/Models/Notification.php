<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'category',
        'target_audience',
        'channels',
        'action_url',
        'read_at',
        'dispatched_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'channels' => 'array',
            'read_at' => 'datetime',
            'dispatched_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
