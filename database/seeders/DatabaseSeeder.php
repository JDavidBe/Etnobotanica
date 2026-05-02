<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Ejecutar con: php artisan db:seed
     */
    public function run(): void
    {
        // ⚠️ El orden importa: respetar dependencias de claves foráneas.
        // Users debe ir antes que auditoria_log.
        // Categorias → Subtemas → Plantas
        $this->call([
            // Spatie: roles y permisos PRIMERO (UsersSeeder los necesita)
            RolesAndPermissionsSeeder::class,
            UsersSeeder::class,
            CategoriasSeeder::class,
            SubtemasSeeder::class,
            PlantasSeeder::class,
            AuditoriaLogSeeder::class,
        ]);
    }
}
