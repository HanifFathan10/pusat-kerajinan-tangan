<?php

namespace App\Policies;

use App\Models\LaporanKeuangan;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LaporanKeuanganPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Administrator', 'Tim Keuangan']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LaporanKeuangan $laporanKeuangan): bool
    {
        return $user->hasAnyRole(['Administrator', 'Tim Keuangan']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Administrator', 'Tim Keuangan']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LaporanKeuangan $laporanKeuangan): bool
    {
        return $user->hasAnyRole(['Administrator', 'Tim Keuangan']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LaporanKeuangan $laporanKeuangan): bool
    {
        return $user->hasAnyRole(['Administrator', 'Tim Keuangan']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, LaporanKeuangan $laporanKeuangan): bool
    {
        return $user->hasAnyRole(['Administrator', 'Tim Keuangan']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, LaporanKeuangan $laporanKeuangan): bool
    {
        return $user->hasAnyRole(['Administrator', 'Tim Keuangan']);
    }
}
