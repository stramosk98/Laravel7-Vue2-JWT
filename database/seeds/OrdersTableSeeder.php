<?php

use Illuminate\Database\Seeder;
use App\Models\Order;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Order::create([
            'client_id' => 1,
            'address' => 'Rua Lauro moura',
            'status' => 1,
        ]);

        Order::create([
            'client_id' => 2,
            'address' => 'Rua João Rodrigues',
            'status' => 1,
        ]);

        Order::create([
            'client_id' => 3,
            'address' => 'Rua 15 de Novembro',
            'status' => 1,
        ]);
    }
}
