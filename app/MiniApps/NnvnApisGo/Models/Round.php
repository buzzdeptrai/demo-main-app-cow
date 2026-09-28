<?php

namespace App\MiniApps\NnvnApisGo\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Round extends Model
{
    const UPDATED_AT = null;

    protected $table = 'nnvn_rounds';

    protected $fillable = [
        'game_id',
        'round_number',
        'time_seconds',
        'quiz_used',
    ];

    protected $casts = [
        'quiz_used' => 'boolean',
        'time_seconds' => 'decimal:1',
        'round_number' => 'integer',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'game_id');
    }
}
