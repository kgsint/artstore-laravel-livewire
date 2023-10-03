<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderConfirmController extends Controller
{
    public function __invoke(Request $request, Order $order)
    {
        return view('orders.guest', compact('order'));
    }
}
