<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class PanduanKerja extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Panduan & SOP';
    protected static ?string $navigationGroup = 'Manajemen Produksi';
    protected static ?int $navigationSort = 4;
    protected static string $view = 'filament.pages.panduan-kerja';

    public static function canView(): bool
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return $user && $user->hasAnyRole(['Administrator', 'Pusat Pengelola', 'Pekerja']);
    }
}
