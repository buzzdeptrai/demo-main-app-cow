<?php

namespace App\Filament\Widgets;

use App\Models\MiniApp;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Spatie\Activitylog\Models\Activity;

class StatsOverview extends BaseWidget
{
    public static function canView(): bool
    {
        return !auth()->user()?->isClient();
    }

    protected function getCards(): array
    {
        return [
            Card::make('Total Users', User::count())
                ->description('Active: ' . User::where('status', 'active')->count())
                ->color('success'),

            Card::make('Mini Apps', MiniApp::count())
                ->description('Active: ' . MiniApp::active()->count())
                ->color('primary'),

            Card::make('Activities Today', Activity::whereDate('created_at', today())->count())
                ->description('Admin actions')
                ->color('warning'),
        ];
    }
}
