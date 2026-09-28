<?php

namespace App\Filament\Widgets;

use App\MiniApps\NnvnApisGo\Models\Game;
use App\MiniApps\NnvnApisGo\Models\Player;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Facades\DB;

class NnvnStatsWidget extends BaseWidget
{
    protected static ?int $sort = 10;

    protected function getCards(): array
    {
        $totalPlayers = Player::count();
        $totalCompleted = Game::where('status', 'completed')->count();
        $totalApisFound = Player::sum('total_apis_found');
        $avgGameTime = Game::where('status', 'completed')
            ->whereNotNull('total_time')
            ->avg('total_time');

        return [
            Card::make('Total Players', $totalPlayers)
                ->description('NNVN Apis Go')
                ->color('primary')
                ->icon('heroicon-o-user-group'),

            Card::make('Games Completed', $totalCompleted)
                ->description('Total completed games')
                ->color('success')
                ->icon('heroicon-o-check-circle'),

            Card::make('Total APIs Found', $totalApisFound)
                ->description('Sum across all players')
                ->color('warning')
                ->icon('heroicon-o-collection'),

            Card::make('Avg Game Time', $avgGameTime ? round($avgGameTime, 1) . 's' : '-')
                ->description('Completed games')
                ->color('danger')
                ->icon('heroicon-o-clock'),
        ];
    }
}
