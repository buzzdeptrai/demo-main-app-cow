<?php

namespace App\Filament\Resources\MiniAppResource\Pages;

use App\Filament\Resources\MiniAppResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMiniApp extends CreateRecord
{
    protected static string $resource = MiniAppResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['creator_id'] = auth()->id();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
