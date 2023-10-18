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
            /**
             *  I am using pgsql as db in deployment and is case-sensitive by default
             * if db is mysql, is case-insensitive and no need to check these orWhere conditions
            */
            $searchedItem = Product::
                                where('title', 'LIKE', "%". ucfirst($this->search) ."%")
                                ->orWhere('title', 'LIKE', "%" . strtolower($this->search) . "%")
                                ->orWhere('title', 'LIKE', "%" . strtoupper($this->search) . "%")
                                ->get();

            return  $searchedItem ?? false;
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
