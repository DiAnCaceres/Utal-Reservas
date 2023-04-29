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
            // fk del usuario que la reserva
            $table->foreignId('usuario_id');
            // fk del bloque que esta reservado
            $table->foreignId('bloque_id')->constrained();

            // recordar que la primary es compuesta
            $table->primary(array('fecha_reserva','usuario_id','bloque_id'));
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