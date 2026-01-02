<?php

namespace App\Filament\Resources\JadwalProduksiResource\Pages;

use App\Filament\Resources\JadwalProduksiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateJadwalProduksi extends CreateRecord
{
    protected static string $resource = JadwalProduksiResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
