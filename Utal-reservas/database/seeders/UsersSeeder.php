<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'Sr. Administrador',
                'rut' => '11.111.111-1',
                'numero_matricula'=>null,
                'email' => 'administrador@utalca.cl',
                'role' => 1,
                'password' => '$2y$10$QhMMhf9EjYcHA83r4uDT/.sMnb4oNF5ZmtSytSPBmWqeTXHaWoFQ2',
            ],
            [
                'id' => 2,
                'name' => 'Sr. Moderador',
                'rut' => '22.222.222-2',
                'numero_matricula'=>null,
                'email' => 'moderador@utalca.cl',
                'role' => 2,
                'password' => '$2y$10$CYg2utzpf/HcRcOP0DbJjepMQdkaNThxjYTnq9Fys6zOt.3BkODtm',
            ],
            [
                'id' => 3,
                'name' => 'Sr. Estudiante id 3',
                'rut' => '33.333.333-3',
                'numero_matricula'=>'2023204003',
                'email' => 'estudianteid3@utalca.cl',
                'role' => 3,
                'password' => '$2y$10$n00aZJ.73HusAOLE39ZQKuttWDCpGOrGwPubG4kAUUc7PgaWA494q',
            ],
            [
                'id' => 4,
                'name' => 'Sr. Estudiante id 4',
                'rut' => '44.444.444-4',
                'numero_matricula'=>'2023204004',
                'email' => 'estudianteid4@utalca.cl',
                'role' => 3,
                'password' => '$2y$10$n00aZJ.73HusAOLE39ZQKuttWDCpGOrGwPubG4kAUUc7PgaWA494q',
            ],
            [
                'id' => 5,
                'name' => 'Sr. Estudiante id 5',
                'rut' => '55.555.555-5',
                'numero_matricula'=>'2023204005',
                'email' => 'estudianteid5@utalca.cl',
                'role' => 3,
                'password' => '$2y$10$n00aZJ.73HusAOLE39ZQKuttWDCpGOrGwPubG4kAUUc7PgaWA494q',
            ],
            [
                'id' => 6,
                'name' => 'Sr. Estudiante id 6',
                'rut' => '66.666.666-6',
                'numero_matricula'=>'2023204003',
                'email' => 'estudianteid6@utalca.cl',
                'role' => 3,
                'password' => '$2y$10$n00aZJ.73HusAOLE39ZQKuttWDCpGOrGwPubG4kAUUc7PgaWA494q',
            ],
            [
                'id' => 7,
                'name' => 'Sr. Estudiante id 7',
                'rut' => '77.777.777-7',
                'numero_matricula'=>'2023204003',
                'email' => 'estudianteid7@utalca.cl',
                'role' => 3,
                'password' => '$2y$10$n00aZJ.73HusAOLE39ZQKuttWDCpGOrGwPubG4kAUUc7PgaWA494q',
            ],
            [
                'id' => 8,
                'name' => 'Sr. Estudiante id 8',
                'rut' => '88.888.888-8',
                'numero_matricula'=>'2020407001',
                'email' => 'estudianteid8@utalca.cl',
                'role' => 3,
                'password' => '$2y$10$n00aZJ.73HusAOLE39ZQKuttWDCpGOrGwPubG4kAUUc7PgaWA494q',
            ],
            [
                'id' => 9,
                'name' => 'Sr. Estudiante id 9',
                'rut' => '99.999.999-9',
                'numero_matricula'=>'2020407001',
                'email' => 'estudianteid9@utalca.cl',
                'role' => 3,
                'password' => '$2y$10$n00aZJ.73HusAOLE39ZQKuttWDCpGOrGwPubG4kAUUc7PgaWA494q',
            ],


        ]);
    }
}
