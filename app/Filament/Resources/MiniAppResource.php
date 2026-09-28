<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MiniAppResource\Pages;
use App\Filament\Resources\MiniAppResource\RelationManagers;
use App\Models\MiniApp;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Str;

class MiniAppResource extends Resource
{
    protected static ?string $model = MiniApp::class;

    public static function shouldRegisterNavigation(): bool
    {
        return !auth()->user()->isClient();
    }

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'App Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Card::make()->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('slug', Str::slug($state));
                    }),

                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->maxLength(1000)
                    ->rows(3),

                Forms\Components\Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'maintenance' => 'Maintenance',
                    ])
                    ->default('active')
                    ->required(),

                Forms\Components\TextInput::make('version')
                    ->default('1.0.0')
                    ->maxLength(20),

                Forms\Components\TextInput::make('api_rate_limit')
                    ->numeric()
                    ->default(60)
                    ->minValue(1)
                    ->maxValue(1000),

                Forms\Components\TextInput::make('webhook_url')
                    ->url()
                    ->maxLength(500),

                Forms\Components\SpatieMediaLibraryFileUpload::make('logo')
                    ->collection('logo')
                    ->image(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'warning' => 'inactive',
                        'danger' => 'maintenance',
                    ]),

                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Creator')
                    ->sortable(),

                Tables\Columns\TextColumn::make('version'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'maintenance' => 'Maintenance',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\SettingsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMiniApps::route('/'),
            'create' => Pages\CreateMiniApp::route('/create'),
            'edit' => Pages\EditMiniApp::route('/{record}/edit'),
        ];
    }
}
