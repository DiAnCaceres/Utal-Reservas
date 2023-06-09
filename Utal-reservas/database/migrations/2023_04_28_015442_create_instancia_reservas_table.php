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
        Schema::create('instancia_reservas', function (Blueprint $table) {
            $table->date('fecha_reserva');
            // fk a reserva
            $table->foreignId('reserva_id')->constrained();;
            // fk del usuario que la reserva
            $table->foreignId('user_id')->constrained();;
            // fk del bloque que esta reservado
            $table->foreignId('bloque_id')->constrained();

            // recordar que la primary es compuesta
            $table->primary(array('fecha_reserva','reserva_id','user_id','bloque_id'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instancia_reservas');
    }
};
