<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $roleAdmin      = Role::create(['name' => 'Administrator']);
        $rolePengelola  = Role::create(['name' => 'Pusat Pengelola']);
        $roleFinance    = Role::create(['name' => 'Tim Keuangan']);
        $rolePekerja    = Role::create(['name' => 'Pekerja']);


        $admin = User::create([
            'name'     => 'Super Admin PKT',
            'email'    => 'admin@pkt.com',
            'password' => Hash::make('password123'),
        ]);
        $admin->assignRole($roleAdmin);

        $manager = User::create([
            'name'     => 'Budi Manager',
            'email'    => 'manager@pkt.com',
            'password' => Hash::make('password123'),
        ]);
        $manager->assignRole($rolePengelola);

        $finance = User::create([
            'name'     => 'Adinda Finance',
            'email'    => 'finance@pkt.com',
            'password' => Hash::make('password123'),
        ]);
        $finance->assignRole($roleFinance);

        $worker = User::create([
            'name'     => 'Asep Pengrajin',
            'email'    => 'asep@pkt.com',
            'password' => Hash::make('password123'),
        ]);
        $worker->assignRole($rolePekerja);
    }
}
