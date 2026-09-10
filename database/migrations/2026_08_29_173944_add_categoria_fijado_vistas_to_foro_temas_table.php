<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('foro_temas', function (Blueprint $table) {
            $table->string('categoria', 30)->default('general')->after('user_id');
            $table->boolean('fijado')->default(false)->after('cerrado');
            $table->unsignedInteger('vistas')->default(0)->after('fijado');
        });
    }

    public function down(): void
    {
        Schema::table('foro_temas', function (Blueprint $table) {
            $table->dropColumn(['categoria', 'fijado', 'vistas']);
        });
    }
};