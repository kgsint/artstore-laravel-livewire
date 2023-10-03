<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderIndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return view('orders.index', [
            'orders' => $request->user()->orders()->latest()
                        ->with('variations.product.media', 'variations.parent.media', 'variations.ancestorsAndSelf', 'shippingType')
                        ->get(),
        ]);
    }
}
