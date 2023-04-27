<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        
        // Acá agregamos los roles
        $rolAdministrador = Role::create(['name' => 'Administrador']);
        $rolModerador = Role::create(['name' => 'Moderador']);
        $rolEstudiante = Role::create(['name' => 'Estudiante']);
        
        /* OJO!! IDEALMENTE LLAMAR LOS NOMBRES DE PERMISOS CON LA MISMA RUTA QUE REALIZA
        DICHA FUNCIONALIDAD PARA QUE SEA MÁS LÓGICO*/
        Permission::create(['name' => 'administrador.dashboard'])->syncRoles([$rolAdministrador]);
        Permission::create(['name' => 'moderador.dashboard'])->syncRoles([$rolModerador]);
        Permission::create(['name' => 'estudiante.dashboard'])->syncRoles([$rolEstudiante]);
            
        Permission::create(['name' => 'administrador.registrarModerador'])->syncRoles([$rolAdministrador]);
        Permission::create(['name' => 'moderador.registrarServicio'])->syncRoles([$rolModerador]);
    
        // Luego: php artisan migrate:fresh --seed 
        
    }
}
