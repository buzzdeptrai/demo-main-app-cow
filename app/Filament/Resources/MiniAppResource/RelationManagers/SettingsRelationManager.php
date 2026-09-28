<?php

namespace App\Filament\Resources\MiniAppResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class SettingsRelationManager extends RelationManager
{
    protected static string $relationship = 'settings';

    protected static ?string $recordTitleAttribute = 'key';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('key')
                ->required()
                ->maxLength(255),

            Forms\Components\Textarea::make('value')
                ->maxLength(5000),

            Forms\Components\Select::make('type')
                ->options([
                    'string' => 'String',
                    'integer' => 'Integer',
                    'boolean' => 'Boolean',
                    'json' => 'JSON',
                ])
                ->default('string')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('value')
                    ->limit(50),

                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'primary' => 'string',
                        'success' => 'integer',
                        'warning' => 'boolean',
                        'danger' => 'json',
                    ]),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
