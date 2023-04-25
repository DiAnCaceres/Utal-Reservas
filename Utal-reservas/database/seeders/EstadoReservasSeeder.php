<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoReservasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
    DB::table('estado_reservas')->insert([
            [
                'id' => 1,
                'nombre_estado' => 'deshabilitado',
            ],
            [
                'id' => 2,
                'nombre_estado' => 'habilitado',
            ]
        ]);
    }
}
    