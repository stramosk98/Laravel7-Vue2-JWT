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
        Client::firstOrCreate(
            ['email' => 'cliente1@exemplo.com'],
            [
                'name' => 'Cliente 1',
                'phone' => '1234567890',
                'city' => 'São Paulo',
                'state' => 'SP',
            ]
        );

        Client::firstOrCreate(
            ['email' => 'cliente2@exemplo.com'],
            [
                'name' => 'Cliente 2',
                'phone' => '1234567890',
                'city' => 'Rio de Janeiro',
                'state' => 'RJ',
            ]
        );

        Client::firstOrCreate(
            ['email' => 'cliente3@exemplo.com'],
            [
                'name' => 'Cliente 3',
                'phone' => '1234567890',
                'city' => 'Santa Catarina',
                'state' => 'SC',
            ]
        );

        Client::firstOrCreate(
            ['email' => 'cliente4@exemplo.com'],
            [
                'name' => 'Cliente 4',
                'phone' => '1234567890',
                'city' => 'Paraná',
                'state' => 'PR',
            ]
        );

        Client::firstOrCreate(
            ['email' => 'cliente5@exemplo.com'],
            [
                'name' => 'Cliente 5',
                'phone' => '1234567890',
                'city' => 'Bahia',
                'state' => 'BA',
            ]
        );
    }
}
