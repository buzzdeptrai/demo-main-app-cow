<?php

namespace App\Filament\Resources\Nnvn\NnvnGameResource\RelationManagers;

use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class RoundsRelationManager extends RelationManager
{
    protected static string $relationship = 'rounds';

    protected static ?string $recordTitleAttribute = 'round_number';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('round_number')
                    ->label('Round')
                    ->sortable(),

                Tables\Columns\TextColumn::make('time_seconds')
                    ->label('Time')
                    ->formatStateUsing(fn ($state) => "{$state}s")
                    ->sortable(),

                Tables\Columns\BooleanColumn::make('quiz_used')
                    ->label('Quiz Used'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('round_number', 'asc')
            ->filters([])
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }
}
