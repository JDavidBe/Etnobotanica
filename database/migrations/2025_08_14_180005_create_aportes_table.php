<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aportes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_planta', 150);
            $table->string('cientifico', 150)->default('');
            $table->string('categoria', 100);
            $table->string('uso', 255);
            $table->text('preparacion');
            $table->text('relato')->nullable();
            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
            $table->string('enviado_por', 100)->default('Anónimo');
            $table->string('ip_origen', 45)->nullable();
            $table->timestamp('creado_en')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aportes');
    }
};
