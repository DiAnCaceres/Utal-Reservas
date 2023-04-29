<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoInstanciaReservaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('estado_instancia_reservas')->insert([
            [
                'id'=>1,
               'nombre_estado'=>'reservado',
            ],
            [
                'id'=>2,
                'nombre_estado'=>'en progreso',
            ],
            [
                'id'=>3,
                'nombre_estado'=>'asiste',
            ],
            [
                'id'=>4,
                'nombre_estado'=>'no asiste',
            ],
            [
                'id'=>5,
                'nombre_estado'=>'cancelado',
            ],
        ]);
    }
}
