<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UbicacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('ubicaciones')->insert([
                [
                    'id' => 1,
                    'nombre_ubicacion' => 'edificio 1',
                    'categoria' => 'educativo',
                ]
        ]);
    }
}
    