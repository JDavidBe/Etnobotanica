<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Descripción de usos tradicionales (medicinales, culinarios,
     * artesanales u otros) y hábitat típico de cada planta, tomada del
     * cuestionario de etnobotánica (Región del Sumapaz). Se muestra en
     * la ficha, en una tarjeta propia entre la taxonomía y las
     * categorías/usos.
     */
    public function up(): void
    {
        Schema::table('plantas', function (Blueprint $table) {
            $table->text('descripcion')->nullable()->after('taxonomia_nota');
        });
    }

    public function down(): void
    {
        Schema::table('plantas', function (Blueprint $table) {
            $table->dropColumn('descripcion');
        });
    }
};
