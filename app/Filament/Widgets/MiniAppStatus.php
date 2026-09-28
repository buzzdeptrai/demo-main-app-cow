<?php

namespace App\Filament\Widgets;

use App\Models\MiniApp;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class MiniAppStatus extends BaseWidget
{
    protected static ?int $sort = 3;

    protected function getCards(): array
    {
        $active = MiniApp::where('status', 'active')->count();
        $inactive = MiniApp::where('status', 'inactive')->count();
        $maintenance = MiniApp::where('status', 'maintenance')->count();

        return [
            Card::make('Active Apps', $active)
                ->color('success')
                ->description('Running normally'),

            Card::make('Inactive Apps', $inactive)
                ->color('warning')
                ->description('Currently disabled'),

            Card::make('Maintenance Apps', $maintenance)
                ->color('danger')
                ->description('Under maintenance'),
        ];
    }
}
