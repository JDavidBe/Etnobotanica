<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Tabla pivote plantas ↔ categorías (muchos a muchos) ──────────
        Schema::create('categoria_planta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('planta_id')->constrained('plantas')->cascadeOnDelete();
            $table->foreignId('categoria_id')->constrained('categorias')->cascadeOnDelete();
            $table->unique(['planta_id', 'categoria_id']);
        });

        // Migrar relación actual (categoria_id) a la tabla pivote
        DB::statement('
            INSERT INTO categoria_planta (planta_id, categoria_id)
            SELECT id, categoria_id FROM plantas WHERE categoria_id IS NOT NULL
        ');

        // ── 2. Campos para créditos y validación de video ───────────────────
        Schema::table('plantas', function (Blueprint $table) {
            $table->string('video_persona_nombre', 150)->nullable()->after('video_url');
            $table->string('video_persona_rol', 100)->nullable()->after('video_persona_nombre');
            $table->boolean('video_validado')->default(false)->after('video_persona_rol');
        });

        // ── 3. Tabla para múltiples imágenes en aportes ─────────────────────
        Schema::create('aporte_imagenes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aporte_id')->constrained('aportes')->cascadeOnDelete();
            $table->string('img_path', 500);
            $table->unsignedSmallInteger('orden')->default(0);
            $table->timestamp('creado_en')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::table('plantas', function (Blueprint $table) {
            $table->dropColumn(['video_persona_nombre', 'video_persona_rol', 'video_validado']);
        });
        Schema::dropIfExists('categoria_planta');
        Schema::dropIfExists('aporte_imagenes');
    }
};
