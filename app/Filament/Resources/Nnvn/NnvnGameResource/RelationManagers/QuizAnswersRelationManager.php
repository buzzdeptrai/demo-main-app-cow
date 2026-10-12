<?php

namespace App\Filament\Resources\Nnvn\NnvnGameResource\RelationManagers;

use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class QuizAnswersRelationManager extends RelationManager
{
    protected static string $relationship = 'quizAnswers';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $title = 'Quiz Answers';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('question.question_vi')
                    ->label('Question')
                    ->limit(60)
                    ->tooltip(fn ($record) => optional($record->question)->question_vi)
                    ->wrap(),

                Tables\Columns\TextColumn::make('answer_index')
                    ->label('Chosen Answer')
                    ->formatStateUsing(fn ($state, $record) => self::optionText($record->question, $state)),

                Tables\Columns\TextColumn::make('correct_answer')
                    ->label('Correct Answer')
                    ->getStateUsing(fn ($record) => self::optionText($record->question, optional($record->question)->correct_index)),

                Tables\Columns\BooleanColumn::make('is_correct')
                    ->label('Correct?'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Answered At')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'asc')
            ->filters([])
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }

    protected static function optionText($question, $index): string
    {
        if (!$question || $index === null) {
            return '-';
        }

        $options = $question->options ?? [];
        $letter = chr(65 + (int) $index);
        $text = $options[$index] ?? '';

        return trim("{$letter}. {$text}");
    }
}
