<?php

namespace App\MiniApps\NnvnApisGo\Models;

use App\MiniApps\NnvnApisGo\Constants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Player extends Model
{
    protected $table = 'nnvn_players';

    protected $fillable = [
        'name',
        'email',
        'round_apis_found',
        'gift_apis_found',
        'total_sessions',
        'best_total_time',
    ];

    protected $casts = [
        'round_apis_found' => 'integer',
        'gift_apis_found' => 'integer',
        'total_sessions' => 'integer',
        'best_total_time' => 'decimal:1',
    ];

    public function games(): HasMany
    {
        return $this->hasMany(Game::class, 'player_id');
    }

    /**
     * Check if the player can still play (has not found all APIs).
     */
    public function canPlay(): bool
    {
        return $this->round_apis_found < Constants::MAX_APIS;
    }

    /**
     * Get the number of round APIs remaining to be found.
     */
    public function remainingRoundApis(): int
    {
        return max(0, Constants::MAX_APIS - $this->round_apis_found);
    }

    /**
     * Get total APIs (round + gift).
     */
    public function totalApis(): int
    {
        return $this->round_apis_found + $this->gift_apis_found;
    }
}
