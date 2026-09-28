<?php

namespace App\Filament\Pages;

use App\MiniApps\NnvnApisGo\Models\BoxClick;
use App\MiniApps\NnvnApisGo\Models\Game;
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

        return compact('boxClicks', 'quizStats', 'summary');
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
        return [
            'total_apis' => Game::where('status', 'completed')->sum('apis_found'),
            'total_games' => Game::where('status', 'completed')->count(),
            'total_box_clicks' => BoxClick::count(),
            'apis_clicks' => BoxClick::where('is_apis', true)->count(),
        ];
    }
}
