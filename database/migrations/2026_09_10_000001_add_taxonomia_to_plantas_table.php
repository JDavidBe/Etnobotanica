<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Añade la clasificación taxonómica completa a cada planta, para la
     * sección "Enciclopedia Botánica": Reino, División, Clase, Orden,
     * Familia y Género. La Especie ya existe como `cientifico`.
     *
     * También se agrega:
     *  - taxonomia_nota: aclaración sobre el nivel de certeza de la
     *    identificación (p. ej. "identificación tentativa", "no
     *    determinado con certeza", fuente consultada, etc.)
     *  - foto_referencia_url: enlace externo a un banco de imágenes de
     *    libre uso (Wikimedia Commons) cuando la planta no cuenta con
     *    fotografía propia cargada (img_url / img_path).
     */
    public function up(): void
    {
        Schema::table('plantas', function (Blueprint $table) {
            $table->string('reino', 100)->nullable()->after('cientifico');
            $table->string('division', 100)->nullable()->after('reino');
            $table->string('clase', 100)->nullable()->after('division');
            $table->string('orden', 100)->nullable()->after('clase');
            $table->string('familia', 100)->nullable()->after('orden');
            $table->string('genero', 100)->nullable()->after('familia');
            $table->text('taxonomia_nota')->nullable()->after('genero');
            $table->string('foto_referencia_url', 500)->nullable()->after('img_path');
        });
    }

    public function down(): void
    {
        Schema::table('plantas', function (Blueprint $table) {
            $table->dropColumn([
                'reino', 'division', 'clase', 'orden', 'familia', 'genero',
                'taxonomia_nota', 'foto_referencia_url',
            ]);
        });
    }
};
