<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        User::firstOrCreate(
            ['email' => 'admin@exemplo.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('123456'),
                'role_id' => 1,
            ]
        );

        User::firstOrCreate(
            ['email' => 'gerente@exemplo.com'],
            [
                'name' => 'Gerente de Logística',
                'password' => Hash::make('123456'),
                'role_id' => 2,
            ]
        );

        User::firstOrCreate(
            ['email' => 'usuario@exemplo.com'],
            [
                'name' => 'Usuário Comum',
                'password' => Hash::make('123456'),
                'role_id' => 3,
            ]
        );
    }
}
