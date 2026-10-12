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
        return $form->schema([
            \Filament\Forms\Components\Card::make()
                ->columns(3)
                ->schema([
                    \Filament\Forms\Components\Placeholder::make('player_name')
                        ->label('Player')
                        ->content(fn ($record) => optional($record->player)->name ?? '-'),

                    \Filament\Forms\Components\Placeholder::make('status')
                        ->label('Status')
                        ->content(fn ($record) => ucfirst($record->status)),

                    \Filament\Forms\Components\Placeholder::make('total_time')
                        ->label('Total Time')
                        ->content(fn ($record) => $record->total_time ? "{$record->total_time}s" : '-'),

                    \Filament\Forms\Components\Placeholder::make('apis_found')
                        ->label('APIs Found')
                        ->content(fn ($record) => $record->apis_found),

                    \Filament\Forms\Components\Placeholder::make('gift_apis_found')
                        ->label('Gift Awarded')
                        ->content(fn ($record) => $record->gift_apis_found ? 'Yes' : 'No'),

                    \Filament\Forms\Components\Placeholder::make('quiz_count')
                        ->label('Quizzes Used')
                        ->content(fn ($record) => $record->quiz_count),

                    \Filament\Forms\Components\Placeholder::make('total_clicks')
                        ->label('Total Box Clicks')
                        ->content(fn ($record) => $record->boxClicks()->count()),

                    \Filament\Forms\Components\Placeholder::make('apis_clicks')
                        ->label('Apis/Gift Hits')
                        ->content(fn ($record) => $record->boxClicks()->where('is_apis', true)->count()),

                    \Filament\Forms\Components\Placeholder::make('quiz_correct')
                        ->label('Quiz Correct')
                        ->content(fn ($record) => $record->quizAnswers()->where('is_correct', true)->count()
                            . ' / ' . $record->quizAnswers()->count()),
                ]),
        ]);
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
            RelationManagers\BoxClicksRelationManager::class,
            RelationManagers\QuizAnswersRelationManager::class,
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
