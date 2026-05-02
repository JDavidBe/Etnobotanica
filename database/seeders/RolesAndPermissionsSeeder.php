<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        try {
            app()[PermissionRegistrar::class]->forgetCachedPermissions();
        } catch (\Exception $e) {
            // Si la tabla de cache aún no existe, no bloqueamos el seeding.
        }

        Role::create(['name' => 'lector']);
        Role::create(['name' => 'moderador']);
        Role::create(['name' => 'admin']);
    }
}