<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstanciaReservas extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('instancia_reservas')->insert([
            // reserva de sala de estudio
            [
                'fecha_reserva' => '2023-05-04',
                'reserva_id' => 1,
                'user_id' => 3,
                'bloque_id' => 1,
            ],
            [
                'fecha_reserva' => '2023-05-04',
                'reserva_id' => 3,
                'user_id' => 5,
                'bloque_id' => 1,
            ],
            // reserva de sala de gimnasio
            [
                'fecha_reserva' => '2023-05-04',
                'reserva_id' => 11,
                'user_id' => 6,
                'bloque_id' => 2,
            ],
            [
                'fecha_reserva' => '2023-05-04',
                'reserva_id' => 11,
                'user_id' => 7,
                'bloque_id' => 2,
            ],
            [
                'fecha_reserva' => '2023-05-04',
                'reserva_id' => 12,
                'user_id' => 8,
                'bloque_id' => 2,
            ],
            // reserva de cancha
            [
                'fecha_reserva' => '2023-05-04',
                'reserva_id' => 21,
                'user_id' => 8,
                'bloque_id' => 3,
            ],
            // reserva de implemento
            [
                'fecha_reserva' => '2023-05-04',
                'reserva_id' => 31,
                'user_id' => 9,
                'bloque_id' => 4,
            ],
            [
                'fecha_reserva' => '2023-05-04',
                'reserva_id' => 31,
                'user_id' => 4,
                'bloque_id' => 4,
            ],
            [
                'fecha_reserva' => '2023-05-04',
                'reserva_id' => 32,
                'user_id' => 5,
                'bloque_id' => 4,
            ],

        ]);
    }
}
