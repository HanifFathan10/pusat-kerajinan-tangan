<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPembelianBahan extends Model
{
    protected $table = 'detail_pembelian_bahan';
    protected $fillable = ['pembelian_bahan_baku_id', 'bahan_baku_id', 'jumlah_beli', 'harga_satuan', 'sub_total'];

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_baku_id');
    }
}
