<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('tipo', 40);         // nuevo_aporte | aporte_aprobado | aporte_rechazado | like_comentario
            $table->string('titulo', 200);
            $table->text('mensaje');
            $table->string('url', 400)->nullable();
            $table->boolean('leida')->default(false);
            $table->timestamp('creado_en')->useCurrent();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('notificaciones'); }
};
