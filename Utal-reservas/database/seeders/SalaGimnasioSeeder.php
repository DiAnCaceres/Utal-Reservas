<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalaGimnasioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sala_gimnasios')->insert([
            [
                'capacidad'=>2,
                'reserva_id'=>11,
            ],
            [
                'capacidad'=>2,
                'reserva_id'=>12,
            ],
            [
                'capacidad'=>6,
                'reserva_id'=>13,
            ],
            [
                'capacidad'=>8,
                'reserva_id'=>14,
            ],
            [
                'capacidad'=>10,
                'reserva_id'=>15,
            ],
            [
                'capacidad'=>10,
                'reserva_id'=>16,
            ],
            [
                'capacidad'=>8,
                'reserva_id'=>17,
            ],
            [
                'capacidad'=>6,
                'reserva_id'=>18,
            ],
            [
                'capacidad'=>4,
                'reserva_id'=>19,
            ],
            [
                'capacidad'=>2,
                'reserva_id'=>20,
            ],
        ]);
    }
}
