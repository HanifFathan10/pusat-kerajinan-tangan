<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';

    protected $fillable = [
        'tanggal',
        'total_harga',
        'jumlah_bayar',
        'kembalian',
        'catatan',
        'status_pembayaran',
        'status_verifikasi',
        'id_pelanggan',
        'id_tim_keuangan',
        'id_penjualan'
    ];

    protected static function booted()
    {
        static::updated(function ($penjualan) {
            if ($penjualan->status_pembayaran === 'lunas') {
                $totalPendapatan = Penjualan::whereMonth('tanggal', now()->month)
                    ->whereYear('tanggal', now()->year)
                    ->sum('total_harga');

                $totalPengeluaran = 0;

                LaporanKeuangan::updateOrCreate(
                    ['periode_laporan' => now()->startOfMonth()->format('Y-m-d')],
                    [
                        'total_pendapatan' => $totalPendapatan,
                        'total_pengeluaran' => $totalPengeluaran,
                        'laba_rugi' => $totalPendapatan - $totalPengeluaran,
                        'id_tim_keuangan' => $penjualan->id_tim_keuangan,
                    ]
                );
            }
        });
    }

    public function detailPenjualan(): HasMany
    {
        return $this->hasMany(DetailPenjualan::class, 'id_penjualan');
    }

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }

    public function timKeuangan(): BelongsTo
    {
        return $this->belongsTo(TimKeuangan::class, 'id_tim_keuangan');
    }
}
