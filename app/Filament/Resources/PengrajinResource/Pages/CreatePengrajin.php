<?php

namespace App\Filament\Resources\PengrajinResource\Pages;

use App\Filament\Resources\PengrajinResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreatePengrajin extends CreateRecord
{
    protected static string $resource = PengrajinResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $data = $this->form->getRawState();
        $pengrajin = $this->record;

        $user = User::create([
            'name'     => $pengrajin->nama_pengrajin,
            'email'    => $pengrajin->email_pengrajin,
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole('Pekerja');
    }
}
