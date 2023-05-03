<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CanchasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('canchas')->insert([
           [
               'id'=>1,
               'reserva_id'=>21,
           ],
            [
                'id'=>2,
                'reserva_id'=>22,
           ],
            [
                'id'=>3,
                'reserva_id'=>23,
           ],
            [
                'id'=>4,
                'reserva_id'=>24,
           ],
            [
                'id'=>5,
                'reserva_id'=>25,
           ],
            [
                'id'=>6,
                'reserva_id'=>26,
           ],
            [
                'id'=>7,
                'reserva_id'=>27,
           ],
            [
                'id'=>8,
                'reserva_id'=>28,
           ],
            [
                'id'=>9,
                'reserva_id'=>29,
           ],
            [
                'id'=>10,
                'reserva_id'=>30,
           ],

        ]);
    }
}
