<?php

namespace App\Filament\Resources\Nnvn\NnvnQuizQuestionResource\Pages;

use App\Filament\Resources\Nnvn\NnvnQuizQuestionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNnvnQuizQuestions extends ListRecords
{
    protected static string $resource = NnvnQuizQuestionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
