<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('historial_instancia_reservas', function (Blueprint $table) {
            $table->date('fecha_reserva');
            $table->unsignedBigInteger('reserva_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('bloque_id');
            $table->dateTime('fecha_estado');
            $table->foreignId('estado_instancia_id')->constrained();

            $table->foreign('fecha_reserva')->references('fecha_reserva')->on('instancia_reservas')->onDelete('cascade');
            $table->foreign('reserva_id')->references('reserva_id')->on('instancia_reservas')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('instancia_reservas')->onDelete('cascade');
            $table->foreign('bloque_id')->references('bloque_id')->on('instancia_reservas')->onDelete('cascade');
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
