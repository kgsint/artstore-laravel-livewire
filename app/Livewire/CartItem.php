<?php

namespace App\Livewire;

use App\Cart\CartService;
use App\Cart\Contracts\CartServiceInterface;
use App\Models\ProductVariation;
use Livewire\Component;

class CartItem extends Component
{
    public ProductVariation $variation;

    public $quantity = null;

    public function mount()
    {
        $this->quantity = $this->variation->pivot->quantity;
    }

    // update quantity in cart item
    public function updatedQuantity()
    {
        // update quantity in database
        app(CartServiceInterface::class)
                                        ->changeQuantity($this->variation, $this->quantity);

        // dispatching event to component
        $this->dispatch('update.cart-item')->to(Cart::class);
        // notify
        $this->dispatch('notification', [
            'body' => 'Quantity updated',
            'timeout' => 1000,
        ]);
    }

    public function removeCartItem()
    {
        app(CartServiceInterface::class)->remove($this->variation);

        // dispatch event to refresh livewire component
        $this->dispatch('remove.cart-item');
    }

    public function render()
    {
        return view('livewire.cart-item');
    }
}
