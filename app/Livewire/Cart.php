<?php

namespace App\Livewire;

use App\Cart\Contracts\CartServiceInterface;
use Livewire\Component;

class Cart extends Component
{
    public $listeners = [
        'remove.cart-item' => '$refresh',
        'update.cart-item' => '$refresh',
    ];


    public function render(CartServiceInterface $cart)
    {
        return view('livewire.cart', compact('cart'));
    }
}
