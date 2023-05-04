<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalaEstudioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sala_estudios')->insert([
           [
                'id'=>1,
                'capacidad'=>2,
                'reserva_id'=>1,
           ],
            [
                'id'=>2,
                'capacidad'=>5,
                'reserva_id'=>2,
           ],
            [
                'id'=>3,
                'capacidad'=>8,
                'reserva_id'=>3,
           ],
            [
                'id'=>4,
                'capacidad'=>8,
                'reserva_id'=>4,
           ],
            [
                'id'=>5,
                'capacidad'=>4,
                'reserva_id'=>5,
           ],
            [
                'id'=>6,
                'capacidad'=>4,
                'reserva_id'=>6,
           ],
            [
                'id'=>7,
                'capacidad'=>10,
                'reserva_id'=>7,
           ],
            [
                'id'=>8,
                'capacidad'=>10,
                'reserva_id'=>8,
           ],
            [
                'id'=>9,
                'capacidad'=>5,
                'reserva_id'=>9,
           ],
            [
                'id'=>10,
                'capacidad'=>10,
                'reserva_id'=>10,
           ],
        ]);
    }
}
