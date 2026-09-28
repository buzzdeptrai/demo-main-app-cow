<?php

namespace App\Filament\Resources\Nnvn\NnvnBoxConfigResource\Pages;

use App\Filament\Resources\Nnvn\NnvnBoxConfigResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNnvnBoxConfig extends EditRecord
{
    protected static string $resource = NnvnBoxConfigResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
