<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JadwalProduksi extends Model
{
    protected $table = 'jadwal_produksi';

    protected $fillable = [
        'tanggal_mulai',
        'tanggal_selesai',
        'status_produksi',
        'jumlah_target',
        'prioritas',
        'catatan',

        'id_pengrajin',
        'id_produk',

        'hasil_produksi',
        'jumlah_reject',
        'biaya_tenaga_kerja',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }

    public function pengrajin(): BelongsTo
    {
        return $this->belongsTo(Pengrajin::class, 'id_pengrajin');
    }

    public function jadwalBahan(): HasMany
    {
        return $this->hasMany(JadwalBahan::class, 'jadwal_produksi_id');
    }

    public function bahanBaku(): BelongsToMany
    {
        return $this->belongsToMany(BahanBaku::class, 'jadwal_bahan', 'jadwal_produksi_id', 'bahan_baku_id')
            ->withPivot('jumlah_dipakai');
    }
}
