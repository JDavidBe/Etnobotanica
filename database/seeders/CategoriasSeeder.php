<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriasSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Medicina',     'icono' => 'fas fa-briefcase-medical', 'descripcion' => 'Saberes curativos locales',          'orden' => 1],
            ['nombre' => 'Alimentación', 'icono' => 'fas fa-seedling',          'descripcion' => 'Nutrición y soberanía alimentaria',   'orden' => 2],
            ['nombre' => 'Cultura',      'icono' => 'fas fa-hand-holding-heart',     'descripcion' => 'Identidad y tradición',               'orden' => 3],
            ['nombre' => 'Enciclopedia Botánica', 'icono' => 'fas fa-book-open', 'descripcion' => 'Conocimiento científico y tradicional de las plantas', 'orden' => 4],
        ];

        foreach ($categorias as $categoria) {
            DB::table('categorias')->updateOrInsert(
                ['nombre' => $categoria['nombre']],
                array_merge($categoria, ['creado_en' => now()])
            );
        }
    }
}
