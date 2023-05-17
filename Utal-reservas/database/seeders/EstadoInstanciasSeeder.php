<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoInstanciasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('estado_instancias')->insert([
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
            [
                'id'=>6,
                'nombre_estado'=>'cancelada debido emergencia',
            ],
        ]);
    }
}
