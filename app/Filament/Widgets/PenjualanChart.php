<?php

namespace App\Filament\Widgets;

use App\Models\Penjualan;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PenjualanChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Tren Pendapatan';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $startDate = $this->filters['startDate'] ?? now()->startOfYear();
        $endDate = $this->filters['endDate'] ?? now()->endOfYear();

        $data = Trend::model(Penjualan::class)
            ->between(
                start: $startDate,
                end: $endDate,
            )
            ->perMonth()
            ->sum('total_harga');

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => Carbon::parse($value->date)->format('M Y')),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    public static function canView(): bool
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return $user && $user->hasAnyRole(['Administrator', 'Pusat Pengelola', 'Tim Keuangan']);
    }
}
