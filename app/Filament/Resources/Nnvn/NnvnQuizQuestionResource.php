<?php

namespace App\Filament\Resources\Nnvn;

use App\Filament\Resources\Nnvn\NnvnQuizQuestionResource\Pages;
use App\MiniApps\NnvnApisGo\Models\QuizQuestion;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class NnvnQuizQuestionResource extends Resource
{
    protected static ?string $model = QuizQuestion::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationGroup = 'NNVN Apis Go';

    protected static ?int $navigationSort = 3;

    protected static ?string $label = 'Quiz Question';

    protected static ?string $pluralLabel = 'Quiz Questions';

    protected static ?string $slug = 'nnvn/quiz-questions';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Card::make()->schema([
                Forms\Components\Textarea::make('question_vi')
                    ->label('Question (Vietnamese)')
                    ->required()
                    ->maxLength(1000)
                    ->rows(3),

                Forms\Components\Textarea::make('question_en')
                    ->label('Question (English)')
                    ->required()
                    ->maxLength(1000)
                    ->rows(3),

                Forms\Components\TagsInput::make('options')
                    ->label('Options (Vietnamese)')
                    ->placeholder('Add option')
                    ->required(),

                Forms\Components\TagsInput::make('options_en')
                    ->label('Options (English)')
                    ->placeholder('Add option'),

                Forms\Components\Select::make('correct_index')
                    ->label('Correct Answer Index')
                    ->options([
                        0 => 'A - Option 1',
                        1 => 'B - Option 2',
                        2 => 'C - Option 3',
                        3 => 'D - Option 4',
                        4 => 'E - Combo (multi-answer)',
                    ])
                    ->required(),

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
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('question_vi')
                    ->label('Question (VI)')
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\TextColumn::make('question_en')
                    ->label('Question (EN)')
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\TextColumn::make('correct_index')
                    ->label('Answer')
                    ->sortable(),

                Tables\Columns\BooleanColumn::make('is_active')
                    ->label('Active')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNnvnQuizQuestions::route('/'),
            'create' => Pages\CreateNnvnQuizQuestion::route('/create'),
            'edit' => Pages\EditNnvnQuizQuestion::route('/{record}/edit'),
        ];
    }
}
