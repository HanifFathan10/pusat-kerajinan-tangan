<?php

namespace App\Filament\Resources\PengrajinResource\Pages;

use App\Filament\Resources\PengrajinResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengrajin extends EditRecord
{
    protected static string $resource = PengrajinResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
