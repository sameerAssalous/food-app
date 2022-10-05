<?php
declare(strict_types=1);

namespace Module\Order\Repositories;

use Module\Order\Models\Order;

class OrderRepository
{
    public function saveOrder(array $productsArray)
    {
        $order = Order::create(
            ['status' => Order::NEW_STATUS]
        );
        foreach ($productsArray as $product){
            $order->products()->attach($product['product_id'], ['quantity' => $product['quantity']]);
        }
        return $order->load('products');
    }
}
