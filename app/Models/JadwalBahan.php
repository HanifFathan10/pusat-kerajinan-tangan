<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalBahan extends Model
{
    protected $table = 'jadwal_bahan';

    protected $fillable = [
        'jadwal_produksi_id',
        'bahan_baku_id',
        'jumlah_dipakai', // Kolom penting ini
    ];

    // Relasi balik ke Bahan Baku (untuk ambil nama bahan di form)
    public function bahanBaku(): BelongsTo
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_baku_id');
    }
}
