<?php

namespace App\Filament\Resources\JadwalProduksiResource\Pages;

use App\Filament\Resources\JadwalProduksiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJadwalProduksis extends ListRecords
{
    protected static string $resource = JadwalProduksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
