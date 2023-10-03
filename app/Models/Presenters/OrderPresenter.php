<?php

namespace App\Models\Presenters;

use App\Models\Order;

class OrderPresenter
{
    public function __construct(
        private Order $order,
    ){}

    public function present()
    {
        return match($this->order->status()) {
            'placed_at' => 'Order Placed',
            'packaged_at' => 'Order Packaged',
            'shipped_at' => 'Order Shipped',
        };
    }
}
