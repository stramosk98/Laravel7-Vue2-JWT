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
        ]);

        User::create([
            'name' => 'Usuário Teste',
            'email' => 'usuario@exemplo.com',
            'password' => Hash::make('123456'),
        ]);
    }
}
