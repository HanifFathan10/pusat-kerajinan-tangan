<?php

namespace App\Filament\Resources\BahanBakuResource\Pages;

use App\Filament\Resources\BahanBakuResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBahanBaku extends CreateRecord
{
    protected static string $resource = BahanBakuResource::class;
    protected static ?string $title = 'Tambah Bahan Baku Baru';
    protected static ?string $breadcrumb = 'Tambah Bahan Baku';
    protected static ?string $navigationLabel = 'Tambah Bahan Baku';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
