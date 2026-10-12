<?php

namespace App\Filament\Resources\Nnvn\NnvnGameResource\RelationManagers;

use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class BoxClicksRelationManager extends RelationManager
{
    protected static string $relationship = 'boxClicks';

    protected static ?string $recordTitleAttribute = 'box_index';

    protected static ?string $title = 'Box Clicks';

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

                Tables\Columns\TextColumn::make('box_index')
                    ->label('Box')
                    ->sortable(),

                Tables\Columns\BooleanColumn::make('is_apis')
                    ->label('Apis/Gift'),

                Tables\Columns\TextColumn::make('url_opened')
                    ->label('Link Opened')
                    ->url(fn ($state) => $state ?: null, true)
                    ->limit(40)
                    ->tooltip(fn ($state) => $state)
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('clicked_at')
                    ->label('Clicked At')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
            ])
            ->defaultSort('clicked_at', 'asc')
            ->filters([])
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }
}
