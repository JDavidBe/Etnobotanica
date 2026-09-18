<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Plantas del Sumapaz — videos de referencia, clasificadas por uso.
 *
 * Fuente: "plantas_sumapaz_videos_clasificadas.docx". Cuando una planta
 * tiene más de un uso (p. ej. Fique: medicina, alimentación y cultura),
 * queda repetida con un registro por cada categoría/subtema, tal como
 * indica la nota del documento original.
 *
 * Las instrucciones e información general se basan en fuentes botánicas
 * y etnobotánicas de referencia (no en el contenido literal del video);
 * el video enlazado es solo la referencia audiovisual de cada planta.
 */
class PlantasSumapazUsoSeeder extends Seeder
{
    public function run(): void
    {
        // Subtemas nuevos que no existían: "golpes/heridas" en Medicina
        // y "tejidos/artesanías" en Cultura, para las plantas que no
        // encajaban en Diabetes/Tensión Alta/Digestión ni en los
        // subtemas culturales ya sembrados.
        $medicinaId = DB::table('categorias')->where('nombre', 'Medicina')->value('id');
        $alimentacionId = DB::table('categorias')->where('nombre', 'Alimentación')->value('id');
        $culturaId = DB::table('categorias')->where('nombre', 'Cultura')->value('id');

        if (!$medicinaId || !$alimentacionId || !$culturaId) {
            return;
        }

        DB::table('subtemas')->updateOrInsert(
            ['categoria_id' => $medicinaId, 'nombre' => 'Primeros auxilios y heridas'],
            ['creado_en' => now()]
        );
        DB::table('subtemas')->updateOrInsert(
            ['categoria_id' => $culturaId, 'nombre' => 'Tejidos y artesanías'],
            ['creado_en' => now()]
        );

        $subtema = function (int $categoriaId, string $nombre) {
            return DB::table('subtemas')
                ->where('categoria_id', $categoriaId)
                ->where('nombre', $nombre)
                ->value('id');
        };

        $digestionId   = $subtema($medicinaId, 'Digestión');
        $diabetesId    = $subtema($medicinaId, 'Diabetes');
        $primerosAuxId = $subtema($medicinaId, 'Primeros auxilios y heridas');
        $personasId    = $subtema($alimentacionId, 'Personas');
        $ritualesId    = $subtema($culturaId, 'Rituales y ceremonias');
        $tejidosId     = $subtema($culturaId, 'Tejidos y artesanías');

        $plantas = [
            // ── MEDICINA ──────────────────────────────────────────────
            [
                'nombre'         => 'Cúrcuma',
                'cientifico'     => 'Curcuma longa',
                'categoria_id'   => $medicinaId,
                'subtema_id'     => $digestionId,
                'uso'            => 'Antiinflamatorio y digestivo',
                'instrucciones'  => 'Hervir una cucharadita de cúrcuma en polvo (o un trozo de raíz rallada) en una taza de agua durante 8-10 minutos. Colar y tomar tibia, una o dos veces al día, idealmente con una pizca de pimienta negra para mejorar la absorción.',
                'contexto'       => 'La curcumina, su principal compuesto activo, tiene propiedades antiinflamatorias y antioxidantes que ayudan a aliviar molestias articulares y a apoyar la digestión.',
                'video_url'      => 'https://www.youtube.com/embed/Cx80XXR3thY',
                'img_url'        => 'https://commons.wikimedia.org/wiki/Special:FilePath/Curcuma_longa_flower.jpg',
                'verificada'     => true,
                'tags'           => 'medicina,digestion,sumapaz',
            ],
            [
                'nombre'         => 'Mejorana',
                'cientifico'     => 'Origanum majorana',
                'categoria_id'   => $medicinaId,
                'subtema_id'     => $digestionId,
                'uso'            => 'Alivio digestivo',
                'instrucciones'  => 'Hervir un puñado de hojas frescas (o una cucharadita de hojas secas) en una taza de agua durante 5 minutos. Colar y tomar después de las comidas.',
                'contexto'       => 'Contiene aceites esenciales con efecto carminativo (reduce los gases) y suavemente antiespasmódico, por lo que se usa tradicionalmente para el malestar estomacal.',
                'video_url'      => 'https://www.youtube.com/embed/6jZnLHWqWwI',
                'img_url'        => 'https://thumb.wikimedia.org/wikipedia/commons/thumb/d/d5/Origanum_majorana_%28links%29%2C_Origanum_vulgare_%28rechts%29-1-Josef_Schlaghecken.jpg/960px-Origanum_majorana_%28links%29%2C_Origanum_vulgare_%28rechts%29-1-Josef_Schlaghecken.jpg',
                'verificada'     => true,
                'tags'           => 'medicina,digestion,sumapaz',
            ],
            [
                'nombre'         => 'Nopal',
                'cientifico'     => 'Opuntia ficus-indica',
                'categoria_id'   => $medicinaId,
                'subtema_id'     => $diabetesId,
                'uso'            => 'Control de glicemia y colesterol',
                'instrucciones'  => 'Licuar una penca de nopal fresca (sin espinas) con agua, colar y tomar en ayunas.',
                'contexto'       => 'Es rica en fibra soluble (mucílagos) que ayuda a ralentizar la absorción de azúcares y grasas, por lo que se usa popularmente para apoyar el control del colesterol y la glicemia.',
                'video_url'      => 'https://www.youtube.com/embed/FamdYzWeMh4',
                'img_url'        => 'https://thumb.wikimedia.org/wikipedia/commons/thumb/d/d4/Hint_inciri_-_Indian_fig_-_Opuntia_ficus-indica_01.JPG/960px-Hint_inciri_-_Indian_fig_-_Opuntia_ficus-indica_01.JPG',
                'verificada'     => true,
                'tags'           => 'medicina,diabetes,sumapaz',
            ],
            [
                'nombre'         => 'Aranto',
                'cientifico'     => 'Kalanchoe daigremontiana',
                'categoria_id'   => $medicinaId,
                'subtema_id'     => $primerosAuxId,
                'uso'            => 'Antiinflamatorio tópico para golpes',
                'instrucciones'  => 'Machacar 1-2 hojas frescas hasta formar una pasta y aplicarla sobre la zona golpeada o inflamada, cubriendo con un paño limpio. No se recomienda su ingestión sin supervisión, por su toxicidad en dosis altas.',
                'contexto'       => 'Contiene compuestos con acción antiinflamatoria y cicatrizante de uso tópico; en la tradición popular andina se le conoce como planta "de emergencia" para golpes y hematomas.',
                'video_url'      => 'https://www.youtube.com/embed/w3t5utZuH3k',
                'img_url'        => null,
                'verificada'     => true,
                'tags'           => 'medicina,primeros-auxilios,sumapaz',
            ],
            [
                'nombre'         => 'Fique',
                'cientifico'     => 'Furcraea andina',
                'categoria_id'   => $medicinaId,
                'subtema_id'     => $primerosAuxId,
                'uso'            => 'Cicatrizante y antiséptico leve',
                'instrucciones'  => 'Aplicar unas gotas de la savia que sale del tallo o de las pencas al cortarlas, directamente sobre heridas leves o raspones.',
                'contexto'       => 'Su uso medicinal es secundario frente a su uso como fibra, pero en la medicina popular campesina se le atribuyen propiedades cicatrizantes y antisépticas leves.',
                'video_url'      => 'https://www.youtube.com/embed/rqAWytSqkgE',
                'img_url'        => 'https://thumb.wikimedia.org/wikipedia/commons/thumb/6/66/Fique.jpg/960px-Fique.jpg',
                'verificada'     => true,
                'tags'           => 'medicina,primeros-auxilios,sumapaz',
            ],

            // ── ALIMENTACIÓN ──────────────────────────────────────────
            [
                'nombre'         => 'Cúrcuma',
                'cientifico'     => 'Curcuma longa',
                'categoria_id'   => $alimentacionId,
                'subtema_id'     => $personasId,
                'uso'            => 'Condimento y colorante natural',
                'instrucciones'  => 'Agregar media cucharadita de cúrcuma en polvo a arroces, sopas, guisos o batidos, preferiblemente con un poco de aceite y pimienta para potenciar su absorción.',
                'contexto'       => 'Aporta un color amarillo intenso y un sabor terroso suave; es ampliamente usada como colorante natural en la cocina.',
                'video_url'      => 'https://www.youtube.com/embed/Cx80XXR3thY',
                'img_url'        => 'https://commons.wikimedia.org/wiki/Special:FilePath/Curcuma_longa_flower.jpg',
                'verificada'     => true,
                'tags'           => 'alimentacion,personas,sumapaz',
            ],
            [
                'nombre'         => 'Mejorana',
                'cientifico'     => 'Origanum majorana',
                'categoria_id'   => $alimentacionId,
                'subtema_id'     => $personasId,
                'uso'            => 'Condimento culinario',
                'instrucciones'  => 'Agregar hojas frescas o secas picadas al final de la cocción de carnes, sopas o salsas, para no perder su aroma.',
                'contexto'       => 'Es pariente cercana del orégano, con un sabor más suave y dulce, adoptado también en la cocina colombiana.',
                'video_url'      => 'https://www.youtube.com/embed/6jZnLHWqWwI',
                'img_url'        => 'https://thumb.wikimedia.org/wikipedia/commons/thumb/d/d5/Origanum_majorana_%28links%29%2C_Origanum_vulgare_%28rechts%29-1-Josef_Schlaghecken.jpg/960px-Origanum_majorana_%28links%29%2C_Origanum_vulgare_%28rechts%29-1-Josef_Schlaghecken.jpg',
                'verificada'     => true,
                'tags'           => 'alimentacion,personas,sumapaz',
            ],
            [
                'nombre'         => 'Nopal',
                'cientifico'     => 'Opuntia ficus-indica',
                'categoria_id'   => $alimentacionId,
                'subtema_id'     => $personasId,
                'uso'            => 'Alimento bajo en calorías',
                'instrucciones'  => 'Retirar las espinas, cortar la penca en tiras o cubos, hervir 5-8 minutos y enjuagar para quitar el exceso de baba antes de usar en ensaladas o guisos.',
                'contexto'       => 'Es bajo en calorías y alto en fibra y agua, por lo que se considera un alimento ligero e hidratante.',
                'video_url'      => 'https://www.youtube.com/embed/FamdYzWeMh4',
                'img_url'        => 'https://thumb.wikimedia.org/wikipedia/commons/thumb/d/d4/Hint_inciri_-_Indian_fig_-_Opuntia_ficus-indica_01.JPG/960px-Hint_inciri_-_Indian_fig_-_Opuntia_ficus-indica_01.JPG',
                'verificada'     => true,
                'tags'           => 'alimentacion,personas,sumapaz',
            ],
            [
                'nombre'         => 'Pepa de Neuro (almendro)',
                'cientifico'     => 'Terminalia catappa',
                'categoria_id'   => $alimentacionId,
                'subtema_id'     => $personasId,
                'uso'            => 'Fruto seco comestible',
                'instrucciones'  => 'Tostar las semillas en un sartén sin aceite a fuego medio durante 5-8 minutos, removiendo constantemente hasta que doren.',
                'contexto'       => 'Se consume como fruto seco de merienda; su identificación exacta como especie sigue siendo incierta según las fuentes consultadas.',
                'video_url'      => 'https://www.youtube.com/embed/o8I6PysC4Cg',
                'img_url'        => 'https://thumb.wikimedia.org/wikipedia/commons/thumb/9/90/Terminalia_Catappa_D8348.jpg/960px-Terminalia_Catappa_D8348.jpg',
                'verificada'     => false,
                'tags'           => 'alimentacion,personas,sumapaz',
            ],
            [
                'nombre'         => 'Fique',
                'cientifico'     => 'Furcraea andina',
                'categoria_id'   => $alimentacionId,
                'subtema_id'     => $personasId,
                'uso'            => 'Sustento económico y alimentario indirecto',
                'instrucciones'  => 'No aplica — no es una planta comestible.',
                'contexto'       => 'Aunque no se consume, su venta como fibra sostiene la economía (y por tanto la alimentación) de muchas familias campesinas del Sumapaz.',
                'video_url'      => 'https://www.youtube.com/embed/rqAWytSqkgE',
                'img_url'        => 'https://thumb.wikimedia.org/wikipedia/commons/thumb/6/66/Fique.jpg/960px-Fique.jpg',
                'verificada'     => true,
                'tags'           => 'alimentacion,personas,sumapaz',
            ],

            // ── CULTURA ───────────────────────────────────────────────
            [
                'nombre'         => 'Fique',
                'cientifico'     => 'Furcraea andina',
                'categoria_id'   => $culturaId,
                'subtema_id'     => $tejidosId,
                'uso'            => 'Fibra textil y artesanías tradicionales',
                'instrucciones'  => 'Raspar las hojas para separar la pulpa de la fibra, lavarla y secarla al sol antes de tejerla en costales, cabuyas y artesanías.',
                'contexto'       => 'Esta técnica ancestral sigue siendo parte de la identidad campesina y económica de la región del Sumapaz.',
                'video_url'      => 'https://www.youtube.com/embed/rqAWytSqkgE',
                'img_url'        => 'https://thumb.wikimedia.org/wikipedia/commons/thumb/6/66/Fique.jpg/960px-Fique.jpg',
                'verificada'     => true,
                'tags'           => 'cultura,artesania,sumapaz',
            ],
            [
                'nombre'         => 'Borrachero',
                'cientifico'     => 'Brugmansia sp.',
                'categoria_id'   => $culturaId,
                'subtema_id'     => $ritualesId,
                'uso'            => 'Planta sagrada en rituales chamánicos',
                'instrucciones'  => 'No se incluyen instrucciones de preparación o consumo de esta planta: es altamente tóxica y su ingestión o inhalación puede causar intoxicaciones graves.',
                'contexto'       => 'Es una planta sagrada y temida en la tradición muisca/chibcha, usada antiguamente en rituales chamánicos por sus efectos alucinógenos; hoy también es conocida como base de la "burundanga", usada con fines delictivos.',
                'video_url'      => 'https://www.youtube.com/embed/maCTNBs0jP0',
                'img_url'        => 'https://commons.wikimedia.org/wiki/Special:FilePath/Angel_Trumpets_shrub_--_Brugmansia_suaveolens.jpg',
                'verificada'     => true,
                'tags'           => 'cultura,rituales,sumapaz',
            ],
        ];

        foreach ($plantas as $planta) {
            DB::table('plantas')->updateOrInsert(
                [
                    'nombre'       => $planta['nombre'],
                    'categoria_id' => $planta['categoria_id'],
                    'subtema_id'   => $planta['subtema_id'],
                ],
                array_merge($planta, [
                    'creado_en'      => now(),
                    'actualizado_en' => now(),
                ])
            );
        }
    }
}
