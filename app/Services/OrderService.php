<?php
namespace App\Services;

use App\Models\Order;

class OrderService
{

    public function paginate(array $filter = [])
    {
        return Order::filter($filter)
            ->with('client:id,name')
            ->paginate();
    }

    /**
     * Create a new order.
     *
     * @param  array  $data
     * @return \App\Models\Order
     */
    public function createOrder(array $data)
    {
        return Order::create($data);
    }
}