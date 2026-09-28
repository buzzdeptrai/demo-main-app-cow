<?php

namespace App\MiniApps\NnvnApisGo\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BoxClick extends Model
{
    protected $table = 'nnvn_box_clicks';

    protected $fillable = [
        'game_id',
        'round_number',
        'box_index',
        'is_apis',
        'is_correct',
        'url_opened',
        'clicked_at',
    ];

    protected $casts = [
        'round_number' => 'integer',
        'box_index' => 'integer',
        'is_apis' => 'boolean',
        'is_correct' => 'boolean',
        'clicked_at' => 'datetime',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'game_id');
    }
}
