<?php

namespace App\Filament\Pages;

use App\MiniApps\NnvnApisGo\Constants;
use App\MiniApps\NnvnApisGo\Models\BoxClick;
use App\MiniApps\NnvnApisGo\Models\Game;
use App\MiniApps\NnvnApisGo\Models\Player;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class NnvnReports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'NNVN Apis Go';

    protected static ?int $navigationSort = 5;

    protected static ?string $title = 'Reports';

    protected static ?string $slug = 'nnvn/reports';

    protected static string $view = 'filament.pages.nnvn-reports';

    protected function getViewData(): array
    {
        $boxClicks = $this->getBoxClickReport();
        $quizStats = $this->getQuizReport();
        $summary = $this->getSummary();
        $leaderboard = $this->getLeaderboard();

        return compact('boxClicks', 'quizStats', 'summary', 'leaderboard');
    }

    private function getBoxClickReport()
    {
        return DB::table('nnvn_box_clicks')
            ->select(
                'box_index',
                DB::raw('MAX(url_opened) as url'),
                DB::raw('COUNT(*) as total_clicks'),
                DB::raw('SUM(CASE WHEN is_apis = 1 THEN 1 ELSE 0 END) as apis_found')
            )
            ->groupBy('box_index')
            ->orderByDesc('total_clicks')
            ->get();
    }

    private function getQuizReport()
    {
        return DB::table('nnvn_quiz_answers as a')
            ->join('nnvn_quiz_questions as q', 'a.question_id', '=', 'q.id')
            ->select(
                'q.id',
                'q.question_vi',
                DB::raw('COUNT(*) as total_answers'),
                DB::raw('SUM(CASE WHEN a.is_correct = 1 THEN 1 ELSE 0 END) as correct_count'),
                DB::raw('SUM(CASE WHEN a.is_correct = 0 THEN 1 ELSE 0 END) as wrong_count'),
                DB::raw('ROUND(SUM(CASE WHEN a.is_correct = 1 THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 1) as correct_rate')
            )
            ->groupBy('q.id', 'q.question_vi')
            ->orderBy('correct_rate')
            ->get();
    }

    private function getSummary(): array
    {
        $giftUsed = Game::where('gift_apis_found', true)
            ->where('status', 'completed')
            ->count();
        $giftLimit = Constants::MAX_GIFT_APIS_GLOBAL;

        return [
            'total_apis' => Game::where('status', 'completed')->sum('apis_found'),
            'total_games' => Game::where('status', 'completed')->count(),
            'total_box_clicks' => BoxClick::count(),
            'apis_clicks' => BoxClick::where('is_apis', true)->count(),
            'gift_used' => $giftUsed,
            'gift_limit' => $giftLimit,
            'gift_remaining' => $giftLimit - $giftUsed,
        ];
    }

    private function getLeaderboard()
    {
        return Player::where('total_sessions', '>', 0)
            ->orderByRaw('(round_apis_found + gift_apis_found) DESC')
            ->orderBy('best_total_time', 'asc')
            ->limit(20)
            ->get();
    }
}
