<?php

namespace App\Filament\Resources\Nnvn;

use App\Filament\Resources\Nnvn\NnvnGameResource\Pages;
use App\Filament\Resources\Nnvn\NnvnGameResource\RelationManagers;
use App\MiniApps\NnvnApisGo\Models\Game;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class NnvnGameResource extends Resource
{
    protected static ?string $model = Game::class;

    protected static ?string $navigationIcon = 'heroicon-o-play';

    protected static ?string $navigationGroup = 'NNVN Apis Go';

    protected static ?int $navigationSort = 2;

    protected static ?string $label = 'Game';

    protected static ?string $pluralLabel = 'Games';

    protected static ?string $slug = 'nnvn/games';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('player.name')
                    ->label('Player')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'playing',
                        'success' => 'completed',
                        'danger' => 'abandoned',
                    ]),

                Tables\Columns\TextColumn::make('total_time')
                    ->label('Total Time')
                    ->formatStateUsing(fn ($state) => $state ? "{$state}s" : '-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('apis_found')
                    ->label('APIs Found')
                    ->sortable(),

                Tables\Columns\BooleanColumn::make('gift_apis_found')
                    ->label('Gift API'),

                Tables\Columns\TextColumn::make('quiz_count')
                    ->label('Quizzes')
                    ->sortable(),

                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Completed')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'playing' => 'Playing',
                        'completed' => 'Completed',
                        'abandoned' => 'Abandoned',
                    ]),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from')
                            ->label('From'),
                        \Filament\Forms\Components\DatePicker::make('until')
                            ->label('Until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\RoundsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNnvnGames::route('/'),
            'view' => Pages\ViewNnvnGame::route('/{record}'),
        ];
    }
}
