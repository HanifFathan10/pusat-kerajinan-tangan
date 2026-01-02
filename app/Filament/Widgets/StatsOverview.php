<?php

namespace App\Filament\Widgets;

use App\Models\Penjualan;
use App\Models\Produk;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\Auth;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 1;


    protected function getStats(): array
    {
        $startDate = $this->filters['startDate'] ?? now()->startOfMonth();
        $endDate = $this->filters['endDate'] ?? now()->endOfMonth();

        $omset = Penjualan::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_harga');

        $chartData = Penjualan::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, SUM(total_harga) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total')
            ->toArray();

        $pendingCount = Penjualan::where('status_pembayaran', 'pending')->count();

        $totalPenjualan = Penjualan::whereBetween('created_at', [$startDate, $endDate])->count();

        return [
            Stat::make('Omset Periode Ini', 'Rp ' . number_format($omset, 0, ',', '.'))
                ->description('Total pendapatan sesuai filter')
                ->descriptionIcon('heroicon-m-banknotes')
                ->chart($chartData)
                ->color('success'),

            Stat::make('Pesanan Pending', $pendingCount)
                ->description($pendingCount > 0 ? 'Butuh konfirmasi segera' : 'Aman, tidak ada tanggungan')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingCount > 0 ? 'warning' : 'success'),

            Stat::make('Total Transaksi', $totalPenjualan)
                ->description('Jumlah transaksi dalam periode ini')
                ->descriptionIcon('heroicon-m-shopping-cart'),
        ];
    }

    public static function canView(): bool
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return $user && $user->hasAnyRole(['Administrator', 'Pusat Pengelola', 'Tim Keuangan']);
    }
}
