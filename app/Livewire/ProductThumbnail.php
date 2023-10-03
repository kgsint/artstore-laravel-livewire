<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class ProductThumbnail extends Component
{
    public Product $product;
    public $selectedImageUrl;

    public function mount()
    {
        $this->selectedImageUrl = $this->product->getFirstMediaUrl();
    }

    public function selectThumbnail($url)
    {
        $this->selectedImageUrl = $url;
    }

    public function render()
    {
        return view('livewire.product-thumbnail');
    }
}
