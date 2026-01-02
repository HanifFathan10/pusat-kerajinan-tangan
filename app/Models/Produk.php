<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    protected $fillable = [
        'nama_produk',
        'deskripsi',
        'harga',
        'stok_produk',
        'id_laporan_produksi',
        'gambar_produk',
        'is_active',
        'sku',
        'berat_gram',
    ];

    protected $casts = [
        'gambar_produk' => 'array',
        'is_active' => 'boolean',
        'harga' => 'integer',
        'stok_produk' => 'integer',
    ];

    public function detailPenjualan(): HasMany
    {
        return $this->hasMany(DetailPenjualan::class, 'id_produk');
    }
}
