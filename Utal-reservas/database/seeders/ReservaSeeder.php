<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReservaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('reservas')->insert([
            // salas de estudio
            [
                'id'=>1,
                'nombre'=>'sala estudio id 1',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>1,
            ],
            [
                'id'=>2,
                'nombre'=>'sala estudio id 2',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>2,
            ],
            [
                'id'=>3,
                'nombre'=>'sala estudio id 3',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>3,
            ],
            [
                'id'=>4,
                'nombre'=>'sala estudio id 4',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>4,
            ],
            [
                'id'=>5,
                'nombre'=>'sala estudio id 5',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>5,
            ],
            [
                'id'=>6,
                'nombre'=>'sala estudio id 6',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>5,
            ],
            [
                'id'=>7,
                'nombre'=>'sala estudio id 7',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>4,
            ],
            [
                'id'=>8,
                'nombre'=>'sala estudio id 8',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>3,
            ],
            [
                'id'=>9,
                'nombre'=>'sala estudio id 9',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>2,
            ],
            [
                'id'=>10,
                'nombre'=>'sala estudio id 10',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>1,
            ],
            // ahora las salas de gym
            [
                'id'=>11,
                'nombre'=>'sala gym 1',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>13,
            ],
            [
                'id'=>12,
                'nombre'=>'sala gym 2',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>13,
            ],
            [
                'id'=>13,
                'nombre'=>'sala gym 3',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>13,
            ],
            [
                'id'=>14,
                'nombre'=>'sala gym 4',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>13,
            ],
            [
                'id'=>15,
                'nombre'=>'sala gym 5',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>13,
            ],
            [
                'id'=>16,
                'nombre'=>'sala gym 6',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>13,
            ],
            [
                'id'=>17,
                'nombre'=>'sala gym 7',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>13,
            ],
            [
                'id'=>18,
                'nombre'=>'sala gym 8',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>13,
            ],
            [
                'id'=>19,
                'nombre'=>'sala gym 9',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>13,
            ],
            [
                'id'=>20,
                'nombre'=>'sala gym 10',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>13,
            ],
            // canchas
            [
                'id'=>21,
                'nombre'=>'cancha gym 1',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>13,
            ],
            [
                'id'=>22,
                'nombre'=>'cancha gym 2',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>13,
            ],
            [
                'id'=>23,
                'nombre'=>'cancha gym 3',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>13,
            ],
            [
                'id'=>24,
                'nombre'=>'cancha gym 4',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>13,
            ],
            [
                'id'=>25,
                'nombre'=>'cancha gym 5',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>13,
            ],
            [
                'id'=>26,
                'nombre'=>'cancha exterior 1',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>13,
            ],
            [
                'id'=>27,
                'nombre'=>'cancha exterior 2',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>14,
            ],
            [
                'id'=>28,
                'nombre'=>'cancha exterior 3',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>14,
            ],
            [
                'id'=>29,
                'nombre'=>'cancha exterior 4',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>14,
            ],
            [
                'id'=>30,
                'nombre'=>'cancha exterior 5',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>14,
            ],
            // implementos
            [
                'id'=>31,
                'nombre'=>'implemento generico 1 ',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>13,
            ],
            [
                'id'=>32,
                'nombre'=>'implemento generico 2 ',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>13,
            ],
            [
                'id'=>33,
                'nombre'=>'implemento generico 3 ',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>13,
            ],
            [
                'id'=>34,
                'nombre'=>'implemento generico 4 ',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>13,
            ],
            [
                'id'=>35,
                'nombre'=>'implemento generico 5 ',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>13,
            ],
            [
                'id'=>36,
                'nombre'=>'implemento generico 6 ',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>13,
            ],
            [
                'id'=>37,
                'nombre'=>'implemento generico 7 ',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>13,
            ],
            [
                'id'=>38,
                'nombre'=>'implemento generico 8 ',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>13,
            ],
            [
                'id'=>39,
                'nombre'=>'implemento generico 9 ',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>13,
            ],
            [
                'id'=>40,
                'nombre'=>'implemento generico 10 ',
                'estado_reserva_id'=>2,
                'ubicacione_id'=>13,
            ],
        ]);
    }
}
