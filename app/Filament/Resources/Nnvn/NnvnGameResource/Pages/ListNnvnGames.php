<?php

namespace App\Filament\Resources\Nnvn\NnvnGameResource\Pages;

use App\Filament\Resources\Nnvn\NnvnGameResource;
use Filament\Resources\Pages\ListRecords;

class ListNnvnGames extends ListRecords
{
    protected static string $resource = NnvnGameResource::class;

    protected function getActions(): array
    {
        return [];
    }
}
