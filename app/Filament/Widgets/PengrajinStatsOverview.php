<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class PengrajinStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return $user->hasAnyRole(['Pekerja']);
    }

    protected function getStats(): array
    {
        $user = Auth::user();

        $pengrajin = $user->infoPengrajin;

        if (!$pengrajin) {
            return [];
        }

        $sedangDikerjakan = $pengrajin->riwayatProduksi()
            ->where('status_produksi', '!=', 'selesai')
            ->count();

        $telahSelesai = $pengrajin->riwayatProduksi()
            ->where('status_produksi', 'selesai')
            ->count();

        $totalUpah = $pengrajin->riwayatProduksi()
            ->where('status_produksi', 'selesai')
            ->sum('biaya_tenaga_kerja');

        return [
            Stat::make('Pekerjaan Berjalan', $sedangDikerjakan . ' Pekerjaan')
                ->description('Jadwal produksi yang belum selesai')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('warning'),

            Stat::make('Pekerjaan Selesai', $telahSelesai . ' Pekerjaan')
                ->description('Jadwal produksi yang telah selesai')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),

            Stat::make('Total Upah Diterima', 'Rp ' . number_format($totalUpah, 0, ',', '.'))
                ->description('Berdasarkan biaya tenaga kerja selesai')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('primary'),
        ];
    }
}
