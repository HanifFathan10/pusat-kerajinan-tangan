<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengrajin extends Model
{
    use HasFactory;

    protected $table = 'pengrajin';

    protected $fillable = [
        'nama_pengrajin',
        'email_pengrajin',
        'telepon_pengrajin',
        'alamat_pengrajin',
    ];

    public function riwayatProduksi(): HasMany
    {
        return $this->hasMany(JadwalProduksi::class, 'id_pengrajin');
    }

    protected static function booted()
    {
        static::deleted(function ($pengrajin) {
            User::where('email', $pengrajin->email_pengrajin)->delete();
        });
    }
}
