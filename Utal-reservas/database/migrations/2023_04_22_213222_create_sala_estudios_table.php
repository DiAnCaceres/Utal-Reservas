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
        Schema::create('sala_estudios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->integer('capacidad');
            $table->timestamps();
            
            // fk
            $table->foreignId('reserva_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sala_estudios');
    }
};
