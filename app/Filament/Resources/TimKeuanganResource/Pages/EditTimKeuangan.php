<?php

namespace App\Filament\Resources\TimKeuanganResource\Pages;

use App\Filament\Resources\TimKeuanganResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTimKeuangan extends EditRecord
{
    protected static string $resource = TimKeuanganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
