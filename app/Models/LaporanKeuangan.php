<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanKeuangan extends Model
{
    protected $table = 'laporan_keuangan';

    protected $fillable = [
        'periode_laporan',
        'total_pendapatan',
        'total_pengeluaran',
        'laba_rugi',
        'id_tim_keuangan',
    ];

    protected $casts = [
        'periode_laporan' => 'date',
        'total_pendapatan' => 'decimal:2',
        'total_pengeluaran' => 'decimal:2',
        'laba_rugi' => 'decimal:2',
    ];

    public function timKeuangan(): BelongsTo
    {
        return $this->belongsTo(TimKeuangan::class, 'id_tim_keuangan');
    }
}
