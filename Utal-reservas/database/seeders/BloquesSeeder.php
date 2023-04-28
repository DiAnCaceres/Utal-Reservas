<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BloquesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('bloques')->insert([
            [
                'id' => 1,
                'hora_inicio' => '8:30',
                'hora_fin' => '9:30',
            ],
            [
                'id' => 2,
                'hora_inicio' => '9:40',
                'hora_fin' => '10:40',
            ],
            [
                'id' => 3,
                'hora_inicio' => '10:50',
                'hora_fin' => '11:50',
            ],
            [
                'id' => 4,
                'hora_inicio' => '12:00',
                'hora_fin' => '13:00',
            ],
            [
                'id' => 5,
                'hora_inicio' => '13:10',
                'hora_fin' => '14:10',
            ],
            [
                'id' => 6,
                'hora_inicio' => '14:20',
                'hora_fin' => '15:20',
            ],
            [
                'id' => 7,
                'hora_inicio' => '15:30',
                'hora_fin' => '16:30',
            ],
            [
                'id' => 8,
                'hora_inicio' => '16:40',
                'hora_fin' => '17:40',
            ],
            [
                'id' => 9,
                'hora_inicio' => '17:50',
                'hora_fin' => '18:50',
            ],
            [
                'id' => 10,
                'hora_inicio' => '19:00',
                'hora_fin' => '20:00',
            ],
        ]);
    }
}
