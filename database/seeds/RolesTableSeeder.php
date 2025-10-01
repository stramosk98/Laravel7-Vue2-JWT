<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        Role::create([
            'name' => 'Administrador',
            'description' => 'Administrador do sistema',
        ]);

        Role::create([
            'name' => 'Gerente de Logística',
            'description' => 'Gerencia pedidos',
        ]);

        Role::create([
            'name' => 'Usuário',
            'description' => 'Usuário comum',
        ]);
    }
}
