<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@exemplo.com',
            'password' => Hash::make('123456'),
            'role_id' => 1,
        ]);

        User::create([
            'name' => 'Gerente de Logística',
            'email' => 'gerente@exemplo.com',
            'password' => Hash::make('123456'),
            'role_id' => 2,
        ]);

        User::create([
            'name' => 'Usuário Comum',
            'email' => 'usuario@exemplo.com',
            'password' => Hash::make('123456'),
            'role_id' => 3,
        ]);
    }
}
