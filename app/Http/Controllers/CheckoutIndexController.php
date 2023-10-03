<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exceptions\StockQuantityReduced;
use App\Cart\Contracts\CartServiceInterface;

class CheckoutIndexController extends Controller
{
    public function __invoke(Request $request, CartServiceInterface $cart)
    {
        // verify if the product stock(s) reduced, before checkout payment
        // if it is, sync available stock amount with product_variation_cart pivot table or (user's chosen quantity in cart)
        try {
            $cart->verifyAvailableStockQuantities();
        }catch(StockQuantityReduced $e) {
            $cart->syncAvailableQuantity();
        }

        return view('checkout');
    }
}
