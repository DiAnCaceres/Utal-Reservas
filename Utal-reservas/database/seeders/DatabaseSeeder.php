<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(BloquesSeeder::class);
        $this->call(UbicacionesSeeder::class);
        $this->call(EstadoInstanciaReservaSeeder::class);
        $this->call(EstadoReservasSeeder::class);

        $this->call(RoleSeeder::class);
        $this->call(UsersSeeder::class);

        $this->call(ReservaSeeder::class);

        $this->call(ImplementoSeeder::class);
        $this->call(SalaEstudioSeeder::class);
        $this->call(SalaGimnasioSeeder::class);
        $this->call(CanchasSeeder::class);

        $this->call(InstanciaReservas::class);

    }
}
