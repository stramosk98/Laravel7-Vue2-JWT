<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        Role::firstOrCreate(
            ['name' => 'Administrador'],
            ['description' => 'Administrador do sistema']
        );

        Role::firstOrCreate(
            ['name' => 'Gerente de Logística'],
            ['description' => 'Gerencia pedidos']
        );

        Role::firstOrCreate(
            ['name' => 'Usuário'],
            ['description' => 'Usuário comum']
        );
    }
}
