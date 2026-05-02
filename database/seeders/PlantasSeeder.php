<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlantasSeeder extends Seeder
{
    public function run(): void
    {
        // subtema_id 1 = Diabetes (categoria 1 = Medicina)
        DB::table('plantas')->insert([
            [
                'nombre'         => 'Guayabo de Monte',
                'cientifico'     => 'Psidium guajava',
                'categoria_id'   => 1,
                'subtema_id'     => 1,
                'uso'            => 'Infusión para glucosa',
                'instrucciones'  => 'Hervir 3 hojas tiernas en una taza de agua durante 5 minutos. Colar y tomar en ayunas, una vez al día.',
                'contexto'       => 'El guayabo es rico en polifenoles que ayudan a ralentizar la absorción de glucosa en el torrente sanguíneo.',
                'relato'         => 'Recuerdo que mi tío abuelo siempre cortaba las hojas más verdes antes del mediodía; decía que el sol les quitaba la fuerza.',
                'video_url'      => 'videos/IMG_Borrachero.mp4',
                'img_url'        => 'img/Guayabo.jpg',
                'verificada'     => true,
                'tags'           => 'local,hojas,verificado',
                'creado_en'      => now(),
                'actualizado_en' => now(),
            ],
            [
                'nombre'         => 'Pata de Vaca',
                'cientifico'     => 'Bauhinia forficata',
                'categoria_id'   => 1,
                'subtema_id'     => 1,
                'uso'            => 'Regulador natural de glucosa',
                'instrucciones'  => 'Preparar infusión con 3 hojas secas en agua caliente. Tomar una taza al día durante 30 días.',
                'contexto'       => 'Sus hojas contienen flavonoides que actúan de manera similar a la insulina vegetal.',
                'relato'         => 'Un vecino de la vereda me regaló las semillas hace años. Él decía que la planta cura el cuerpo si uno la cuida con respeto.',
                'video_url'      => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'img_url'        => 'img/pata.jpg',
                'verificada'     => true,
                'tags'           => 'ancestral,hojas',
                'creado_en'      => now(),
                'actualizado_en' => now(),
            ],
            [
                'nombre'         => 'Moringa',
                'cientifico'     => 'Moringa oleifera',
                'categoria_id'   => 1,
                'subtema_id'     => 1,
                'uso'            => 'Control metabólico',
                'instrucciones'  => 'Secar las hojas a la sombra, moler y agregar media cucharadita de polvo al jugo o té del desayuno.',
                'contexto'       => 'Conocida como el árbol de la vida, aporta nutrientes esenciales que fortalecen el páncreas.',
                'relato'         => 'En casa tenemos un árbol inmenso. Mi mamá seca las hojas a la sombra y las muele para echarlas en el jugo.',
                'video_url'      => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'img_url'        => 'img/moringa.webp',
                'verificada'     => false,
                'tags'           => 'eficaz,polvo',
                'creado_en'      => now(),
                'actualizado_en' => now(),
            ],
        ]);
    }
}
