<?php

namespace App\MiniApps\NnvnApisGo\Controllers;

use App\Http\Controllers\Controller;
use App\MiniApps\NnvnApisGo\Models\QuizAnswer;
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
        $gameId = $request->query('game_id');

        if (!in_array($lang, ['vi', 'en'])) {
            return $this->error('Invalid language. Use vi or en.', 400);
        }

        $questionField = $lang === 'en' ? 'question_en' : 'question_vi';
        $optionsField = $lang === 'en' ? 'options_en' : 'options';

        $query = QuizQuestion::where('is_active', true);

        if ($gameId) {
            $recentIds = QuizAnswer::where('game_id', $gameId)
                ->latest('id')
                ->limit(3)
                ->pluck('question_id')
                ->toArray();

            if (!empty($recentIds)) {
                $query->whereNotIn('id', $recentIds);
            }
        }

        $question = $query->inRandomOrder()->first();

        if (!$question) {
            return $this->error('No questions available', 404);
        }

        $options = $question->{$optionsField};

        if (empty($options) && $lang === 'en') {
            $options = $question->options;
        }

        return $this->success([
            'id' => $question->id,
            'question' => $question->{$questionField},
            'options' => $options,
            'lang' => $lang,
        ], 'Question retrieved successfully');
    }

    public function answer(AnswerQuizRequest $request)
    {
        $validated = $request->validated();

        $question = QuizQuestion::findOrFail($validated['question_id']);
        $isCorrect = (int) $validated['answer_index'] === (int) $question->correct_index;

        QuizAnswer::create([
            'game_id' => $validated['game_id'],
            'question_id' => $validated['question_id'],
            'answer_index' => $validated['answer_index'],
            'is_correct' => $isCorrect,
        ]);

        return $this->success([
            'correct' => $isCorrect,
            'correct_index' => $question->correct_index,
        ], $isCorrect ? 'Correct answer!' : 'Wrong answer');
    }
}
