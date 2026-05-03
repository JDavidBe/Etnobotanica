<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comentarios', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['planta', 'aporte']);
            $table->unsignedBigInteger('tipo_id');
            $table->text('contenido');
            $table->string('autor', 100)->default('Anónimo');
            $table->string('ip_origen', 45)->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamp('creado_en')->useCurrent();
        });

        Schema::create('comentario_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comentario_id')->constrained('comentarios')->cascadeOnDelete();
            $table->string('ip_origen', 45);
            $table->timestamp('creado_en')->useCurrent();
            $table->unique(['comentario_id', 'ip_origen']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comentario_likes');
        Schema::dropIfExists('comentarios');
    }
};
