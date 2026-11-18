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

    public function safeActionUrl(): ?string
    {
        $url = $this->action_url;
        if (! $url || preg_match('/[\\\\\r\n]/', $url)) {
            return null;
        }
        if (str_starts_with($url, '/') && ! str_starts_with($url, '//')) {
            return $url;
        }

        return str_starts_with($url, url('/').'/') ? $url : null;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
