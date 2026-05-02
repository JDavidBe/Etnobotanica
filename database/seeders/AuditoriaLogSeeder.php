<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuditoriaLogSeeder extends Seeder
{
    public function run(): void
    {
        // usuario_id 1 corresponde al admin insertado en UsersSeeder
        DB::table('auditoria_log')->insert([
            [
                'accion'     => 'CREÓ',
                'elemento'   => 'Base de datos inicializada',
                'usuario_id' => 1,
                'fecha'      => now(),
            ],
        ]);
    }
}
