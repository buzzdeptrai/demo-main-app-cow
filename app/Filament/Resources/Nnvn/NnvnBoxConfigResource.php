<?php

namespace App\Filament\Resources\Nnvn;

use App\Filament\Resources\Nnvn\NnvnBoxConfigResource\Pages;
use App\MiniApps\NnvnApisGo\Models\BoxConfig;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class NnvnBoxConfigResource extends Resource
{
    protected static ?string $model = BoxConfig::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'NNVN Apis Go';

    protected static ?int $navigationSort = 4;

    protected static ?string $label = 'Box Config';

    protected static ?string $pluralLabel = 'Box Configs';

    protected static ?string $slug = 'nnvn/box-configs';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Card::make()->schema([
                Forms\Components\TextInput::make('box_index')
                    ->label('Box Index')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(7)
                    ->unique(ignoreRecord: true),

                Forms\Components\TextInput::make('url')
                    ->label('URL')
                    ->required()
                    ->url()
                    ->maxLength(500),

                Forms\Components\TextInput::make('label')
                    ->label('Label')
                    ->maxLength(255),

                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('box_index')
                    ->label('Box Index')
                    ->sortable(),

                Tables\Columns\TextColumn::make('url')
                    ->label('URL')
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\TextColumn::make('label')
                    ->label('Label')
                    ->searchable(),

                Tables\Columns\BooleanColumn::make('is_active')
                    ->label('Active')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('box_index', 'asc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => !auth()->user()->isClient()),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->visible(fn () => !auth()->user()->isClient()),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNnvnBoxConfigs::route('/'),
            'create' => Pages\CreateNnvnBoxConfig::route('/create'),
            'edit' => Pages\EditNnvnBoxConfig::route('/{record}/edit'),
        ];
    }
}
