<?php

namespace App\Policies;

use App\Models\JadwalProduksi;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class JadwalProduksiPolicy
{
    /**
     * Menentukan siapa yang bisa melihat daftar monitoring.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Administrator', 'Pusat Pengelola', 'Tim Keuangan', 'Pekerja']);
    }

    /**
     * Menentukan siapa yang bisa melihat detail spesifik jadwal.
     */
    public function view(User $user, JadwalProduksi $jadwalProduksi): bool
    {
        if ($user->hasAnyRole(['Administrator', 'Pusat Pengelola', 'Tim Keuangan'])) {
            return true;
        }

        return $user->hasRole('Pekerja') && $jadwalProduksi->pengrajin->email_pengrajin === $user->email;
    }

    /**
     * Menentukan siapa yang bisa membuat jadwal baru.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Administrator', 'Pusat Pengelola']);
    }

    /**
     * Menentukan siapa yang bisa mengubah data jadwal.
     */
    public function update(User $user, JadwalProduksi $jadwalProduksi): bool
    {
        if ($jadwalProduksi->status_produksi === 'selesai') {
            return false;
        }

        if ($user->hasAnyRole(['Administrator', 'Pusat Pengelola'])) {
            return true;
        }

        return $user->hasRole('Pekerja') && $jadwalProduksi->pengrajin->email_pengrajin === $user->email;
    }

    /**
     * Menentukan siapa yang bisa menghapus jadwal.
     */
    public function delete(User $user, JadwalProduksi $jadwalProduksi): bool
    {
        if (in_array($jadwalProduksi->status_produksi, ['progress', 'selesai'])) {
            return false;
        }

        return $user->hasRole('Administrator');
    }
}
