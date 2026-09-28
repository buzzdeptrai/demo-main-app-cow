<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityResource\Pages;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Spatie\Activitylog\Models\Activity;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    public static function canAccess(): bool
    {
        return !auth()->user()->isClient();
    }

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-list';

    protected static ?string $navigationGroup = 'System';

    protected static ?int $navigationSort = 1;

    protected static ?string $label = 'Activity Log';

    protected static ?string $pluralLabel = 'Activity Logs';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('log_name')
                    ->label('Log')
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Action')
                    ->searchable(),

                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Subject')
                    ->formatStateUsing(function ($state) {
                        if (!$state) {
                            return '-';
                        }

                        return class_basename($state);
                    }),

                Tables\Columns\TextColumn::make('subject_id')
                    ->label('Subject ID'),

                Tables\Columns\TextColumn::make('causer.name')
                    ->label('Caused By')
                    ->default('-'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('log_name')
                    ->options(function () {
                        return Activity::distinct()
                            ->pluck('log_name', 'log_name')
                            ->filter()
                            ->toArray();
                    }),

                Tables\Filters\SelectFilter::make('description')
                    ->options([
                        'created' => 'Created',
                        'updated' => 'Updated',
                        'deleted' => 'Deleted',
                    ]),
            ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivities::route('/'),
        ];
    }
}
