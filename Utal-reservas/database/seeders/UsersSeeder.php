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
                'rut' => 111111111,
                'email' => 'administrador@utalca.cl',
                'role' => 1,
                'password' => '$2y$10$QhMMhf9EjYcHA83r4uDT/.sMnb4oNF5ZmtSytSPBmWqeTXHaWoFQ2',
            ],
        ]);
    }
}
    