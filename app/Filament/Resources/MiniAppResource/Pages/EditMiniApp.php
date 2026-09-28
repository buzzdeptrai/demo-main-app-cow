<?php

namespace App\Filament\Resources\MiniAppResource\Pages;

use App\Filament\Resources\MiniAppResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMiniApp extends EditRecord
{
    protected static string $resource = MiniAppResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
