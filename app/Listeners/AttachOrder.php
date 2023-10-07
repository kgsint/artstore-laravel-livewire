<?php

namespace App\Listeners;

use App\Models\Order;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AttachOrder
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    public function handle(Registered $event): void
    {
        // associate new registered user with their order(s).
        Order::where('email', $event->user->email)->get()->each(function($order) use($event) {
            $order->user()->associate($event->user);
            $order->save();
        });
    }
}
