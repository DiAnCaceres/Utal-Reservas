<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoPenalizacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('estado_penalizaciones')->insert([
            [
                'id'=>1,
               'nombre_estado'=>'injustificado',
            ],
            [
                'id'=>2,
                'nombre_estado'=>'justificado',
            ],
        ]);
    }
}
