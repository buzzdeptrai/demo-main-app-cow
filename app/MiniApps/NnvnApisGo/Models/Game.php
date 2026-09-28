<?php

namespace App\MiniApps\NnvnApisGo\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    const UPDATED_AT = null;

    protected $table = 'nnvn_games';

    protected $fillable = [
        'player_id',
        'status',
        'total_time',
        'quiz_count',
        'apis_found',
        'gift_apis_found',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'gift_apis_found' => 'boolean',
        'total_time' => 'decimal:1',
        'quiz_count' => 'integer',
        'apis_found' => 'integer',
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player_id');
    }

    public function rounds(): HasMany
    {
        return $this->hasMany(Round::class, 'game_id');
    }

    public function boxClicks(): HasMany
    {
        return $this->hasMany(BoxClick::class, 'game_id');
    }

    public function scopePlaying(Builder $query): Builder
    {
        return $query->where('status', 'playing');
    }

    public function scopeCompletedToday(Builder $query): Builder
    {
        return $query->where('status', 'completed')
            ->whereDate('completed_at', today());
    }
}
