<?php

namespace App\Filament\Resources\Nnvn\NnvnPlayerResource\Pages;

use App\Filament\Resources\Nnvn\NnvnPlayerResource;
use Filament\Resources\Pages\ListRecords;

class ListNnvnPlayers extends ListRecords
{
    protected static string $resource = NnvnPlayerResource::class;

    protected function getActions(): array
    {
        return [];
    }
}
