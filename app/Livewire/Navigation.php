<?php

namespace App\Livewire;

use App\Cart\Contracts\CartServiceInterface;
use App\Models\Product;
use Livewire\Attributes\Url;
use Livewire\Component;

class Navigation extends Component
{
    public $listeners = [
        'added-to-cart' => '$refresh',
        'remove.cart-item' => '$refresh',
    ];

    #[Url(as: 's')]
    public $search = '';

    public function getCartProperty(CartServiceInterface $cart): CartServiceInterface
    {
        return $cart;
    }

    public function getProductsProperty()
    {
        if($this->search) {
            return Product::where('title', 'LIKE', "%{$this->search}%")->get() ?? false;
        };

        return false;
    }


    public function render()
    {
        return view('livewire.navigation', [
            'products' => $this->products,
        ]);
    }
}
