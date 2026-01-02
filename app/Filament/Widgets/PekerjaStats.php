<?php

namespace App\Filament\Widgets;

use App\Models\JadwalProduksi;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class PekerjaStats extends BaseWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();

        if ($user && $user->role === 'Pekerja') {
            $query = JadwalProduksi::whereHas('pengrajin', function ($q) use ($user) {
                $q->where('email_pengrajin', $user->email);
            });

            return [
                Stat::make('Tugas Aktif', (clone $query)->where('status_produksi', 'progress')->count())
                    ->description('Pekerjaan yang sedang Anda kerjakan')
                    ->descriptionIcon('heroicon-m-arrow-path')
                    ->color('warning'),

                Stat::make('Tugas Selesai', (clone $query)->where('status_produksi', 'selesai')->count())
                    ->description('Total karya yang telah Anda selesaikan')
                    ->descriptionIcon('heroicon-m-check-badge')
                    ->color('success'),

                Stat::make('Total Pendapatan', 'Rp ' . number_format((clone $query)->sum('biaya_tenaga_kerja'), 0, ',', '.'))
                    ->description('Akumulasi upah Anda bulan ini')
                    ->descriptionIcon('heroicon-m-banknotes')
                    ->color('primary'),
            ];
        }

        return [];
    }

    public static function canView(): bool
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Hanya Pekerja yang bisa melihat statistik pribadinya
        return $user && $user->hasRole('Pekerja');
    }
}
