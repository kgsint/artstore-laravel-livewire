<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;

class ProductsByCategory extends Component
{
    public $filter;

    public function render()
    {
        $categories = Category::query()
                                ->select('categories.*')
                                ->with('products.media')
                                ->rightJoin('category_product', 'categories.id', 'category_product.category_id')
                                ->when($this->filter, fn($query) => $query->where('categories.slug', $this->filter))
                                ->where('categories.parent_id', null)
                                ->groupBy('categories.id')
                                ->orderBy('categories.title', 'asc')
                                ->get();

        return view('livewire.products-by-category', compact('categories'));
    }
}
