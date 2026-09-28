<?php

namespace App\MiniApps\NnvnApisGo\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnswerQuizRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'question_id' => ['required', 'integer', 'exists:nnvn_quiz_questions,id'],
            'answer_index' => ['required', 'integer', 'in:0,1,2'],
            'game_id' => ['required', 'integer', 'exists:nnvn_games,id'],
        ];
    }
}
