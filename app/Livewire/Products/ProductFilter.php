<?php

namespace App\Livewire\Products;

use App\Contracts\ProductInterface;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductFilter extends Component
{
    use WithPagination;

    public $filters = [];

    public function render(ProductInterface $product)
    {
        return view('livewire.products.product-filter', [
            'products' => Product::
                                    with('media')
                                    ->when(count($this->filters), function($query) {
                                        $query->whereHas('variations', function($query) {
                                                 $query->whereIn('title', $this->filters);
                                        });
                                    })
                                    ->latest()
                                    ->paginate(12),
            'uniqueVariations' => $product->getUniqueVariations(),
        ]);
    }

    // check if filters (associative) array is empty
    private function isFiltersEmpty()
    {
        foreach($this->filters as $key => $value) {
            if(empty($value)) {
                return false;
            }

            return true;
        }
    }


}
