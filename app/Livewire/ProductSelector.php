<?php

namespace App\Livewire;

use App\Cart\Contracts\CartServiceInterface;
use App\Models\Product;
use App\Models\ProductVariation;
use Livewire\Attributes\On;
use Livewire\Component;

class ProductSelector extends Component
{
    public Product $product;
    public $initialVariations;

    public $skuVariation; // final vairation

    public function mount()
    {
        $this->initialVariations = $this->product->variations->sortBy('order')->groupBy('type')->first();
    }

    // listening for final product variation selected,
    #[On('skuVariationSelected')]
    public function selectedSkuVariation($variationId)
    {
        if(! $variationId) {
            $this->skuVariation = null;
            return;
        }
        $this->skuVariation = ProductVariation::find($variationId);
    }

    // add to cart
    public function addToCart(CartServiceInterface $cart)
    {
        $cart->add($this->skuVariation);

        // dispatch browser event
        $this->dispatch('notification', [
            'body' => "{$this->skuVariation->product->title} has been added to the cart",
            'timeout' => 2000,
        ]);

        // dispatch event
        $this->dispatch('added-to-cart');
    }

    // view
    public function render()
    {
        return view('livewire.product-selector');
    }
}
