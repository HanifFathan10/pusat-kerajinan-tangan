<?php

namespace App\Filament\Resources\PengrajinResource\Pages;

use App\Filament\Resources\PengrajinResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPengrajins extends ListRecords
{
    protected static string $resource = PengrajinResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
