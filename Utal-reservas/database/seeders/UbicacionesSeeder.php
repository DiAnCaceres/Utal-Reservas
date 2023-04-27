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
                'nombre_ubicacion' => 'Edificio Bienestar Estudiantil',
                'categoria' => 'educativo',
            ],
            [
                'id' => 2,
                'nombre_ubicacion' => 'Edificio Servicios Multiples',
                'categoria' => 'educativo',
            ] ,
            [
                'id' => 3,
                'nombre_ubicacion' => 'Edificio Laboratorios',
                'categoria' => 'educativo',
            ] ,
            [
                'id' => 4,
                'nombre_ubicacion' => 'Edificio Tecnologias IND',
                'categoria' => 'educativo',
            ] ,
            [
                'id' => 5,
                'nombre_ubicacion' => 'Salas E',
                'categoria' => 'educativo',
            ] ,
            [
                'id' => 6,
                'nombre_ubicacion' => 'Auditorio Jorge Ossandon y Biblioteca',
                'categoria' => 'educativo',
            ] ,
            [
                'id' => 7,
                'nombre_ubicacion' => 'Edicio Lab Tecn.',
                'categoria' => 'educativo',
            ] ,
            [
                'id' => 8,
                'nombre_ubicacion' => 'Edificio de Inv. y  Desarrollo I+D',
                'categoria' => 'educativo',
            ] ,
            [
                'id' => 9,
                'nombre_ubicacion' => 'Edificio Salas Metodologias Activas',
                'categoria' => 'educativo',
            ] ,
            [
                'id' => 10,
                'nombre_ubicacion' => 'Edificio Ing. Civil Electrica',
                'categoria' => 'educativo',
            ] ,
            [
                'id' => 11,
                'nombre_ubicacion' => 'Edificio Ing. Civil Minas',
                'categoria' => 'educativo',
            ] ,
            [
                'id' => 12,
                'nombre_ubicacion' => 'Edificio C.T.A',
                'categoria' => 'educativo',
            ] ,
            [
                'id' => 13,
                'nombre_ubicacion' => 'Gimnasio',
                'categoria' => 'deportivo',
            ] ,
            [
                'id' => 14,
                'nombre_ubicacion' => 'Gimnasio',
                'categoria' => 'deportivo',
            ] ,
            
        ]);
    }
}
    