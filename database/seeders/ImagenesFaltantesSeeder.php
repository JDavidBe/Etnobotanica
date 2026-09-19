<?php

namespace Database\Seeders;

use App\Models\Planta;
use Illuminate\Database\Seeder;

/**
 * Rellena `img_url` en las plantas que quedaron sin imagen tras las
 * cargas iniciales (Tabla2PlantasUsoSeeder y PlantasSumapazUsoSeeder).
 *
 * Todas las imágenes provienen de Wikimedia Commons (licencias libres).
 * Es idempotente: solo escribe donde `img_url` e `img_path` están vacíos.
 *
 *   php artisan db:seed --class=ImagenesFaltantesSeeder
 */
class ImagenesFaltantesSeeder extends Seeder
{
    private const CURCUMA_URL = 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/47/Starr-140925-1977-Curcuma_longa-flowering_habit-Pali_o_Waipio_Huelo-Maui_%2825128294462%29.jpg/960px-Starr-140925-1977-Curcuma_longa-flowering_habit-Pali_o_Waipio_Huelo-Maui_%2825128294462%29.jpg';

    /** Imagen por nombre científico (lo más fiable para emparejar). */
    private const POR_CIENTIFICO = [
        'Kalanchoe daigremontiana' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/66/Zyworodka_-_Kalanchoe_daigremontiana.JPG/960px-Zyworodka_-_Kalanchoe_daigremontiana.JPG',
        'Curcuma longa'            => 'https://cdn1.costatic.com/assets/img/guide_achat/articles/curcuma-planta-con-multiples-virtudes_19b0cf18d80b2834.jpg',
        'Cissus verticillata'      => 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/92/Cissus_verticillata_2.jpg/960px-Cissus_verticillata_2.jpg',
        'Terminalia catappa'       => 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/90/Terminalia_Catappa_D8348.jpg/960px-Terminalia_Catappa_D8348.jpg',
        'Brugmansia sp.'           => 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/03/B._arborea_flor-1.JPG/960px-B._arborea_flor-1.JPG',
        'Brugmansia arborea'       => 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/03/B._arborea_flor-1.JPG/960px-B._arborea_flor-1.JPG',
    ];

    /** Respaldo por nombre común, para fichas sin nombre científico firme. */
    private const POR_NOMBRE = [
        'Tres Cogollos' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8e/Zwellende_bloemknoppen_van_Chaenomeles_x_superba_%27nicolina%27_%28chinese_kwee%29._Locatie._Tuinreservaat_Jonkervallei_02.jpg/960px-Zwellende_bloemknoppen_van_Chaenomeles_x_superba_%27nicolina%27_%28chinese_kwee%29._Locatie._Tuinreservaat_Jonkervallei_02.jpg',
        'Tres cogollos' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8e/Zwellende_bloemknoppen_van_Chaenomeles_x_superba_%27nicolina%27_%28chinese_kwee%29._Locatie._Tuinreservaat_Jonkervallei_02.jpg/960px-Zwellende_bloemknoppen_van_Chaenomeles_x_superba_%27nicolina%27_%28chinese_kwee%29._Locatie._Tuinreservaat_Jonkervallei_02.jpg',
    ];

    public function run(): void
    {
        $actualizadas = 0;

        Planta::query()
            ->where('cientifico', 'Curcuma longa')
            ->update([
                'img_url' => self::CURCUMA_URL,
                'img_path' => null,
                'foto_referencia_url' => self::CURCUMA_URL,
            ]);

        Planta::query()
            ->whereNull('img_path')
            ->where(fn ($q) => $q->whereNull('img_url')->orWhere('img_url', ''))
            ->get()
            ->each(function (Planta $planta) use (&$actualizadas) {
                $url = self::POR_CIENTIFICO[trim((string) $planta->cientifico)]
                    ?? self::POR_NOMBRE[trim((string) $planta->nombre)]
                    ?? null;

                if (! $url) {
                    $this->command?->warn("Sin imagen definida: {$planta->nombre}");

                    return;
                }

                $planta->forceFill([
                    'img_url'              => $url,
                    'foto_referencia_url'  => $planta->foto_referencia_url ?: $url,
                ])->save();

                $actualizadas++;
            });

        $this->command?->info("Imágenes asignadas: {$actualizadas}");
    }
}
