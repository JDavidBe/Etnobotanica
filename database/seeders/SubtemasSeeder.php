<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubtemasSeeder extends Seeder
{
    public function run(): void
    {
        // categoria_id: 1=Medicina · 2=Alimentación · 3=Cultura
        DB::table('subtemas')->insert([
            ['categoria_id' => 1, 'nombre' => 'Diabetes',              'creado_en' => now()],
            ['categoria_id' => 1, 'nombre' => 'Tensión Alta',          'creado_en' => now()],
            ['categoria_id' => 1, 'nombre' => 'Digestión',             'creado_en' => now()],
            ['categoria_id' => 2, 'nombre' => 'Personas',              'creado_en' => now()],
            ['categoria_id' => 2, 'nombre' => 'Animales',              'creado_en' => now()],
            ['categoria_id' => 3, 'nombre' => 'Rituales y ceremonias', 'creado_en' => now()],
            ['categoria_id' => 3, 'nombre' => 'Tintes naturales',      'creado_en' => now()],
            ['categoria_id' => 3, 'nombre' => 'Construcción ancestral','creado_en' => now()],
        ]);
    }
}
