<?php

namespace App\Filament\Resources\TimKeuanganResource\Pages;

use App\Filament\Resources\TimKeuanganResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTimKeuangan extends CreateRecord
{
    protected static string $resource = TimKeuanganResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
