<?php

namespace App\MiniApps\NnvnApisGo\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $table = 'nnvn_quiz_questions';

    protected $fillable = [
        'question_vi',
        'question_en',
        'options',
        'correct_index',
        'is_active',
    ];

    protected $casts = [
        'options' => 'array',
        'is_active' => 'boolean',
        'correct_index' => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
