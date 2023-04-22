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
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',30);
            $table->string('ubicacion',40);
            //$table->unsignedBigInteger('estado_reserva_id');
            //$table->unsignedBigInteger('tipo_reserva_id');
            $table->timestamps();
            
            // fk (laravel 10)
            $table->foreignId('estado_reserva_id')->constrained();
            $table->foreignId('tipo_reserva_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
