<?php

namespace App\Observers;

use App\Mail\OrderStatusUpdated;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class OrderObserver
{
    // send user email when the order status is updated
    public function updated(Order $order)
    {
        $filledStatuses = collect($order->getDirty())
                                            ->only($order->statuses)
                                            ->filter(fn($status) => filled($status));

        $originalOrder = new Order(
            collect($order->getOriginal())
                ->only($order->statuses)
                ->toArray()
        );

        if($order->status() !== $originalOrder->status() && $filledStatuses->count()) {
            // Mail::to($order->user)->send(new OrderStatusUpdated($order));
        }
    }
}
