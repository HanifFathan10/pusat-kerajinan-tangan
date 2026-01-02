<?php

namespace App\Policies;

use App\Models\JadwalProduksi;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class JadwalProduksiPolicy
{
    /**
     * Menentukan apakah user bisa melihat daftar (list) jadwal produksi.
     */
    public function viewAny(User $user): bool
    {
        // Semua role yang terdaftar boleh melihat menu ini
        return $user->hasAnyRole(['Administrator', 'Pusat Pengelola', 'Tim Keuangan', 'Pekerja']);
    }

    /**
     * Menentukan apakah user bisa melihat detail jadwal tertentu.
     */
    public function view(User $user, JadwalProduksi $jadwalProduksi): bool
    {
        if ($user->hasAnyRole(['Administrator', 'Pusat Pengelola'])) {
            return true;
        }

        return $user->hasRole('Pekerja') && $jadwalProduksi->pengrajin->email_pengrajin === $user->email;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Administrator', 'Pusat Pengelola']);
    }

    public function update(User $user, JadwalProduksi $jadwalProduksi): bool
    {
        return $user->hasAnyRole(['Administrator', 'Pusat Pengelola']);
    }

    public function delete(User $user, JadwalProduksi $jadwalProduksi): bool
    {
        return $user->hasAnyRole(['Administrator', 'Pusat Pengelola']);
    }
}
