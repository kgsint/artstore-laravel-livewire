<?php

namespace App\Http\Controllers;

use App\Contracts\ProductInterface;
use App\Models\ProductVariation;
use Illuminate\Http\Request;

class ProductIndexController extends Controller
{
    public function __invoke(Request $request, ProductInterface $product)
    {
        return view('products.index', [
            'uniqueVariations' => $product->getUniqueVariations()
        ]);
    }
}
