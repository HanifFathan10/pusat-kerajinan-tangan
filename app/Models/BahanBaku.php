<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BahanBaku extends Model
{
    use HasFactory;

    protected $table = 'bahan_baku';

    protected $guarded = [];

    public function penggunaanProduksi()
    {
        return $this->belongsToMany(JadwalProduksi::class, 'jadwal_bahan');
    }
}
