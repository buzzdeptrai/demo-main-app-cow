<?php

namespace App\Filament\Resources\Nnvn;

use App\Filament\Resources\Nnvn\NnvnPlayerResource\Pages;
use App\Filament\Resources\Nnvn\NnvnPlayerResource\RelationManagers;
use App\MiniApps\NnvnApisGo\Constants;
use App\MiniApps\NnvnApisGo\Models\Player;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class NnvnPlayerResource extends Resource
{
    protected static ?string $model = Player::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'NNVN Apis Go';

    protected static ?int $navigationSort = 1;

    protected static ?string $label = 'Player';

    protected static ?string $pluralLabel = 'Players';

    protected static ?string $slug = 'nnvn/players';

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
        $maxApis = Constants::MAX_APIS;

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('round_apis_found')
                    ->label('Round APIs')
                    ->formatStateUsing(fn ($state) => "{$state}/{$maxApis}")
                    ->sortable(),

                Tables\Columns\TextColumn::make('gift_apis_found')
                    ->label('Gift APIs')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('progress')
                    ->label('Progress')
                    ->getStateUsing(function (Player $record) use ($maxApis) {
                        if ($record->round_apis_found >= $maxApis) {
                            return 'Maxed Out';
                        }
                        if ($record->total_sessions > 0) {
                            return 'Playing';
                        }

                        return 'New';
                    })
                    ->colors([
                        'success' => 'Maxed Out',
                        'warning' => 'Playing',
                        'secondary' => 'New',
                    ]),

                Tables\Columns\TextColumn::make('total_sessions')
                    ->label('Sessions')
                    ->sortable(),

                Tables\Columns\TextColumn::make('best_total_time')
                    ->label('Best Time')
                    ->formatStateUsing(fn ($state) => $state ? "{$state}s" : '-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('rank')
                    ->label('Rank')
                    ->getStateUsing(function (Player $record) {
                        $totalApis = $record->round_apis_found + $record->gift_apis_found;
                        return Player::whereRaw('(round_apis_found + gift_apis_found) > ?', [$totalApis])
                            ->orWhere(function ($q) use ($record, $totalApis) {
                                $q->whereRaw('(round_apis_found + gift_apis_found) = ?', [$totalApis])
                                    ->whereNotNull('best_total_time')
                                    ->where('best_total_time', '<', $record->best_total_time ?? PHP_INT_MAX);
                            })
                            ->count() + 1;
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('round_apis_found', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('progress')
                    ->options([
                        'has_played' => 'Has Played',
                        'maxed_out' => 'Maxed Out',
                    ])
                    ->query(function ($query, array $data) use ($maxApis) {
                        if (!$data['value']) {
                            return $query;
                        }
                        if ($data['value'] === 'has_played') {
                            return $query->where('total_sessions', '>', 0);
                        }
                        if ($data['value'] === 'maxed_out') {
                            return $query->where('round_apis_found', '>=', $maxApis);
                        }

                        return $query;
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
            RelationManagers\GamesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNnvnPlayers::route('/'),
            'view' => Pages\ViewNnvnPlayer::route('/{record}'),
        ];
    }
}
