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
            $table->string('nombre',30)->unique();
            //$table->unsignedBigInteger('estado_reserva_id');
            //$table->unsignedBigInteger('tipo_reserva_id');
            //$table->timestamps();
            
            // fk (laravel 10)
            $table->foreignId('estado_reserva_id')->constrained();
            /* Laravel permite crear las foreing key poniendo el nombre de la tabla en singular_id
            claro esta que en español no todas las palabras suenan bien, como por ejemplo la tabla 
            ubicaciones en singular es ubicacion, pero lo interpretará como ubicacione|s
            */
            $table->foreignId('ubicacione_id')->constrained();
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
