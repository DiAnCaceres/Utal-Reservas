<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('historial_reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instancia_reserva_fecha_reserva');
            $table->foreignId('instancia_reserva_user_id');
            $table->foreignId('instancia_reserva_bloque_id');
            $table->foreignId('estado_instancia_reserva_id');
            $table->date('fecha');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_reservas');
    }
};
