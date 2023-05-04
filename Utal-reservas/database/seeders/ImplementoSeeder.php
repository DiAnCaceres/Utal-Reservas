<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImplementoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('implementos')->insert([
            [
                'id'=>1,
                'cantidad'=>2,
                'reserva_id'=>31,
            ],
            [
                'id'=>2,
                'cantidad'=>2,
                'reserva_id'=>32,
            ],
            [
                'id'=>3,
                'cantidad'=>4,
                'reserva_id'=>33,
            ],
            [
                'id'=>4,
                'cantidad'=>5,
                'reserva_id'=>34,
            ],
            [
                'id'=>5,
                'cantidad'=>6,
                'reserva_id'=>35,
            ],
            [
                'id'=>6,
                'cantidad'=>7,
                'reserva_id'=>36,
            ],
            [
                'id'=>7,
                'cantidad'=>8,
                'reserva_id'=>37,
            ],
            [
                'id'=>8,
                'cantidad'=>9,
                'reserva_id'=>38,
            ],
            [
                'id'=>9,
                'cantidad'=>10,
                'reserva_id'=>39,
            ],
            [
                'id'=>10,
                'cantidad'=>11,
                'reserva_id'=>40,
            ],
        ]);
    }
}
