<?php

namespace App\MiniApps\NnvnApisGo\Controllers;

use App\Http\Controllers\Controller;
use App\MiniApps\NnvnApisGo\Models\QuizQuestion;
use App\MiniApps\NnvnApisGo\Requests\AnswerQuizRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    use ApiResponse;

    public function random(Request $request)
    {
        $lang = $request->query('lang', 'vi');

        if (!in_array($lang, ['vi', 'en'])) {
            return $this->error('Invalid language. Use vi or en.', 400);
        }

        $questionField = $lang === 'en' ? 'question_en' : 'question_vi';

        $question = QuizQuestion::where('is_active', true)
            ->inRandomOrder()
            ->first();

        if (!$question) {
            return $this->error('No questions available', 404);
        }

        return $this->success([
            'id' => $question->id,
            'question' => $question->{$questionField},
            'options' => $question->options,
            'lang' => $lang,
        ], 'Question retrieved successfully');
    }

    public function answer(AnswerQuizRequest $request)
    {
        $validated = $request->validated();

        $question = QuizQuestion::findOrFail($validated['question_id']);
        $isCorrect = (int) $validated['answer_index'] === (int) $question->correct_index;

        return $this->success([
            'correct' => $isCorrect,
            'correct_index' => $question->correct_index,
        ], $isCorrect ? 'Correct answer!' : 'Wrong answer');
    }
}
