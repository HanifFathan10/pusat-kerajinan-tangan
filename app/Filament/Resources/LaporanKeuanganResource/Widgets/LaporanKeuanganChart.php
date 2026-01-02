<?php

namespace App\Filament\Resources\LaporanKeuanganResource\Widgets;

use Filament\Widgets\ChartWidget;

class LaporanKeuanganChart extends ChartWidget
{
    // app/Filament/Resources/LaporanKeuanganResource/Widgets/LaporanKeuanganChart.php

    protected static ?string $heading = 'Tren Arus Kas (12 Bulan Terakhir)';

    protected function getData(): array
    {
        // Ambil data 12 bulan terakhir
        $data = \App\Models\LaporanKeuangan::query()
            ->where('periode_laporan', '>=', now()->subYear())
            ->orderBy('periode_laporan')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Pemasukan (Omset)',
                    'data' => $data->pluck('total_pendapatan'),
                    'borderColor' => '#10b981', // Hijau
                ],
                [
                    'label' => 'Pengeluaran (Cost)',
                    'data' => $data->pluck('total_pengeluaran'),
                    'borderColor' => '#ef4444', // Merah
                ],
            ],
            'labels' => $data->map(fn($item) => $item->periode_laporan->format('M Y')),
        ];
    }

    protected function getType(): string
    {
        return 'line'; // Grafik Garis
    }
}
