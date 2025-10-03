<?php

use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Client::create([
            'name' => 'Cliente 1',
            'email' => 'cliente1@exemplo.com',
            'phone' => '1234567890',
            'city' => 'São Paulo',
            'state' => 'SP',
        ]);

        Client::create([
            'name' => 'Cliente 2',
            'email' => 'cliente2@exemplo.com',
            'phone' => '1234567890',
            'city' => 'Rio de Janeiro',
            'state' => 'RJ',
        ]);

        Client::create([
            'name' => 'Cliente 3',
            'email' => 'cliente3@exemplo.com',
            'phone' => '1234567890',
            'city' => 'Santa Catarina',
            'state' => 'SC',
        ]);

        Client::create([
            'name' => 'Cliente 4',
            'email' => 'cliente4@exemplo.com',
            'phone' => '1234567890',
            'city' => 'Paraná',
            'state' => 'PR',
        ]);

        Client::create([
            'name' => 'Cliente 5',
            'email' => 'cliente5@exemplo.com',
            'phone' => '1234567890',
            'city' => 'Bahia',
            'state' => 'BA',
        ]);
    }
}
