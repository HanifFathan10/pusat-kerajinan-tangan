<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimKeuangan extends Model
{
    protected $table = 'tim_keuangan';

    protected $fillable = [
        'nama_pegawai',
        'email_keuangan',
        'nik_karyawan',
        'jabatan',
        'telepon',
        'foto_profil',
        'is_active',
    ];
}
