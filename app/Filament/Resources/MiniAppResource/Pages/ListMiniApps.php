<?php

namespace App\Filament\Resources\MiniAppResource\Pages;

use App\Filament\Resources\MiniAppResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMiniApps extends ListRecords
{
    protected static string $resource = MiniAppResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
