<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'request_id',
        'sender',
        'content',
        'citations',
        'inline_cards',
        'prompt_suggestions',
    ];

    protected function casts(): array
    {
        return [
            'citations' => 'array',
            'inline_cards' => 'array',
            'prompt_suggestions' => 'array',
        ];
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(AiConversation::class, 'conversation_id', 'id');
    }
}
