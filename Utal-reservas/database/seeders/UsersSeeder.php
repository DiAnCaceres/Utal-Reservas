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
                'name' => 'Sr. Estudiante',
                'rut' => '33.333.333-3',
                'numero_matricula'=>'2020407001',
                'email' => 'estudiante@utalca.cl',
                'role' => 3,
                'password' => '$2y$10$n00aZJ.73HusAOLE39ZQKuttWDCpGOrGwPubG4kAUUc7PgaWA494q',
            ],

        ]);
    }
}
