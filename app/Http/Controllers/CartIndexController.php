<?php

namespace App\Http\Controllers;

use App\Cart\Contracts\CartServiceInterface;
use App\Exceptions\StockQuantityReduced;
use Illuminate\Http\Request;

class CartIndexController extends Controller
{
    public function __invoke(Request $request, CartServiceInterface $cart)
    {
        // verify if the product stock(s) reduced, before checkout payment
        // if it is, sync available stock amount with product_variation_cart pivot table or (user's chosen quantity in cart)
        try {
            $cart->verifyAvailableStockQuantities();
        }catch(StockQuantityReduced $e) {
            // sync
            $cart->syncAvailableQuantity();
        }

        return view('cart');
    }
}
