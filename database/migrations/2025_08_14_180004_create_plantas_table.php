<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plantas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->string('cientifico', 150)->default('sp.');
            $table->foreignId('categoria_id')->constrained('categorias');
            $table->foreignId('subtema_id')->constrained('subtemas');
            $table->string('uso', 255);
            $table->text('instrucciones');
            $table->text('contexto')->nullable();
            $table->text('relato')->nullable();
            $table->string('video_url', 500)->default('');
            $table->string('img_url', 500)->nullable();
            $table->boolean('verificada')->default(false);
            $table->string('tags', 300)->default('');
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plantas');
    }
};
