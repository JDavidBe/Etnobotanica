<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('aportes', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('ip_origen');
            $table->text('motivo_rechazo')->nullable()->after('estado');
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }
    public function down(): void {
        Schema::table('aportes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'motivo_rechazo']);
        });
    }
};
