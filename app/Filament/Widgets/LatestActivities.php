<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Models\Activity;

class LatestActivities extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    protected function getTableQuery(): Builder
    {
        return Activity::query()
            ->latest()
            ->limit(5);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('description')
                ->label('Action'),

            Tables\Columns\TextColumn::make('subject_type')
                ->label('Subject')
                ->formatStateUsing(function ($state) {
                    if (!$state) {
                        return '-';
                    }

                    return class_basename($state);
                }),

            Tables\Columns\TextColumn::make('causer.name')
                ->label('By')
                ->default('-'),

            Tables\Columns\TextColumn::make('created_at')
                ->label('When')
                ->dateTime('d/m/Y H:i')
                ->sortable(),
        ];
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }
}
