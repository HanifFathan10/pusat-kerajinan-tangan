<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PembelianBahanBaku extends Model
{
    protected $table = 'pembelian_bahan_baku';
    protected $fillable = ['tanggal_beli', 'supplier', 'total_biaya'];

    public function detailPembelian(): HasMany
    {
        return $this->hasMany(DetailPembelianBahan::class, 'pembelian_bahan_baku_id');
    }
}
