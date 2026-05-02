<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auditoria_log', function (Blueprint $table) {
            $table->id();
            $table->string('accion', 50);    // 'CREÓ','EDITÓ','ELIMINÓ','PUBLICÓ','RECHAZÓ'
            $table->string('elemento', 200);
            // FK apunta a la tabla users de Laravel (antes: usuarios)
            $table->foreignId('usuario_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamp('fecha')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auditoria_log');
    }
};
