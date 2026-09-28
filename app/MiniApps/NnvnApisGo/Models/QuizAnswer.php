<?php

namespace App\MiniApps\NnvnApisGo\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAnswer extends Model
{
    protected $table = 'nnvn_quiz_answers';

    protected $fillable = [
        'game_id',
        'question_id',
        'answer_index',
        'is_correct',
    ];

    protected $casts = [
        'answer_index' => 'integer',
        'is_correct' => 'boolean',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(QuizQuestion::class, 'question_id');
    }
}
