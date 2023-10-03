<?php

namespace App\Http\Controllers;

use App\Models\ProductVariation;
use Illuminate\Http\Request;

class ProductIndexController extends Controller
{
    public function __invoke(Request $request)
    {
        $variations = ProductVariation::select('type', 'title')
                                    ->whereHas('product', function($query) {
                                        $query->whereNotNull('live_at');
                                    })
                                    ->distinct()
                                    ->get();

        /**
         *              e.g [
         *              'type' => [
         *                  'variation_title1',
         *                  'variation_title2'
         *                      ],
         *                  ]
         */
        $uniqueVariations = [];

        foreach($variations as $v) {
            $type = $v->type;
            $title = $v->title;

            // unique type to set initial empty array
            if(!isset($uniqueVariations[$type])) {
                $uniqueVariations[$type] = [];
            }

            // throw title in type array in $uniqueVariations if not already exists
            if(!in_array($title, $uniqueVariations[$type])) {
                $uniqueVariations[$type][] = $title;
            }
        }

        return view('products.index', compact('uniqueVariations'));
    }
}
