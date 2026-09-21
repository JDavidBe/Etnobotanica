<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Plantas medicinales reportadas por video: Poleo (ronquidos / dificultad
 * para dormir) y Orégano (dolor de oído). El Orégano ya existía en el
 * catálogo por otros usos (digestivo, condimento); este es un uso nuevo,
 * así que se agrega como ficha adicional en "Otros usos medicinales" en
 * vez de sobrescribir las que ya tenía.
 */
class PlantasVideosMedicinaSeeder extends Seeder
{
    public function run(): void
    {
        $medicinaId = DB::table('categorias')->where('nombre', 'Medicina')->value('id');

        if (!$medicinaId) {
            return;
        }

        // Por si este seeder se corre antes que Tabla2PlantasUsoSeeder.
        DB::table('subtemas')->updateOrInsert(
            ['categoria_id' => $medicinaId, 'nombre' => 'Otros usos medicinales'],
            ['creado_en' => now()]
        );

        $subtemaId = DB::table('subtemas')
            ->where('categoria_id', $medicinaId)
            ->where('nombre', 'Otros usos medicinales')
            ->value('id');

        if (!$subtemaId) {
            return;
        }

        $plantas = [
            [
                'nombre'         => 'Poleo',
                'cientifico'     => 'Mentha pulegium',
                'reino'          => 'Plantae',
                'division'       => 'Magnoliophyta',
                'clase'          => 'Magnoliopsida',
                'orden'          => 'Lamiales',
                'familia'        => 'Lamiaceae',
                'genero'         => 'Mentha',
                'uso'            => 'Ayuda a dormir y reduce los ronquidos',
                'instrucciones'  => 'Hervir un manojo de la planta en agua o en leche; dejar reposar y tomar.',
                'contexto'       => 'Reportada como remedio tradicional para personas que roncan por la noche y tienen dificultad para dormir.',
                'video_url'      => 'https://www.youtube.com/embed/7Rqkpv1Z6QY',
                'img_url'        => null,
                'verificada'     => true,
                'tags'           => 'medicina,otros,sumapaz',
            ],
            [
                'nombre'         => 'Orégano',
                'cientifico'     => 'Origanum vulgare',
                'reino'          => 'Plantae',
                'division'       => 'Magnoliophyta',
                'clase'          => 'Magnoliopsida',
                'orden'          => 'Lamiales',
                'familia'        => 'Lamiaceae',
                'genero'         => 'Origanum',
                'uso'            => 'Alivio del dolor de oído',
                'instrucciones'  => 'Limpiar hojas de orégano en buen estado, exprimir su savia, calentarla y aplicar de 2 a 3 gotas en el oído.',
                'contexto'       => 'Reportada como remedio tradicional para el mal de oído. Nota: ante dolor de oído persistente, secreción, fiebre o pérdida de audición, se recomienda consultar a un médico; no aplicar gotas si hay sospecha de perforación del tímpano.',
                'video_url'      => 'https://www.youtube.com/embed/DVLin4-dZBI',
                'img_url'        => 'https://tienda.ecoyuma.com.co/811-large_default/oregano.jpg',
                'verificada'     => true,
                'tags'           => 'medicina,otros,sumapaz',
            ],
        ];

        foreach ($plantas as $planta) {
            DB::table('plantas')->updateOrInsert(
                [
                    'nombre'       => $planta['nombre'],
                    'categoria_id' => $medicinaId,
                    'subtema_id'   => $subtemaId,
                ],
                array_merge($planta, [
                    'categoria_id'   => $medicinaId,
                    'subtema_id'     => $subtemaId,
                    'relato'         => null,
                    'img_path'       => null,
                    'creado_en'      => now(),
                    'actualizado_en' => now(),
                ])
            );
        }
    }
}
