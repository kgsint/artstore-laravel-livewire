<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductShowController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Product $product)
    {
        $product->load('variations.children', 'variations.descendantsAndSelf.stocks', 'variations.media');

        return view('products.show', compact('product'));
    }
}
