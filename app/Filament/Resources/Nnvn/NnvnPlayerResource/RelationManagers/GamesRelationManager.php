<?php

namespace App\Filament\Resources\Nnvn\NnvnPlayerResource\RelationManagers;

use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class GamesRelationManager extends RelationManager
{
    protected static string $relationship = 'games';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Game ID')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'playing',
                        'success' => 'completed',
                        'danger' => 'abandoned',
                    ]),

                Tables\Columns\TextColumn::make('total_time')
                    ->label('Time')
                    ->formatStateUsing(fn ($state) => $state ? "{$state}s" : '-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('apis_found')
                    ->label('APIs Found')
                    ->sortable(),

                Tables\Columns\BooleanColumn::make('gift_apis_found')
                    ->label('Gift API'),

                Tables\Columns\TextColumn::make('quiz_count')
                    ->label('Quizzes'),

                Tables\Columns\TextColumn::make('completed_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([])
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }
}
