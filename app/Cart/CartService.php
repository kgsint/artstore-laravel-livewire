<?php

namespace App\Cart;

use App\Cart\Contracts\CartServiceInterface;
use App\Exceptions\StockQuantityReduced;
use App\Models\Cart;
use App\Models\ProductVariation;
use App\Models\User;
use Illuminate\Session\SessionManager;
use Staudenmeir\LaravelAdjacencyList\Eloquent\Collection;

class CartService implements CartServiceInterface
{
    protected $instance;

    public function __construct(
        private readonly SessionManager $session,
    ){}

    // check session exists or not
    public function exists(): bool
    {
        return $this->session->has(config('cart.session.key')) && $this->instance();
    }

    public function associate(User $user): void
    {
        $this->instance()->user()->associate($user);
        $this->instance()->save();
    }

    // create cart in carts table
    public function create(?User $user): void
    {
        $instance = Cart::make();

        if($user) {
            $instance->user()->associate($user);
        }

        $instance->save();

        $this->session->put(config('cart.session.key'), $instance->uuid);

    }

    // Cart Model instance
    public function instance(): ?Cart
    {
        if($this->instance) {
            return $this->instance;
        }

        return $this->instance = Cart::query()
                                    ->with(
                                        'variations.media',
                                        'variations.ancestorsAndSelf',
                                        'variations.product.media',
                                        'variations.descendantsAndSelf.stocks',
                                    )
                                    ->whereUuid($this->session->get(config('cart.session.key')) ?? '')->first();
    }

    public function contents(): Collection
    {
        return $this->instance()->variations;
    }

    public function contentCount(): int
    {
        return $this->contents()->count();
    }

    public function isEmpty(): bool
    {
        return $this->contentCount() === 0;
    }

    // add to cart (i.e create record in cart_product_variation table)
    public function add(ProductVariation $variation, $quantity = 1): void
    {
        // update quantity if product variation already exists in the cart (cart_variation pivot table)
        if($currentVariation = $this->getCurrentProductVariation($variation)) {
            $quantity += $currentVariation->pivot->quantity;
        }

        // sync in pivot table without detaching(removing the record in the pivot table)
        $this->instance()->variations()->syncWithoutDetaching([
            $variation->id => [
                'quantity' => min($variation->stockCount(), $quantity)
            ]
        ]);
    }

    public function getCurrentProductVariation(ProductVariation $variation): ?ProductVariation
    {
        return $this->instance()->variations->find($variation->id);
    }

    // update
    public function changeQuantity(ProductVariation $variation, int $quantity): void
    {
        // update quantity in database
        $this->instance()->variations()->updateExistingPivot($variation->id, [
            'quantity' => $quantity
        ]);
    }

    // remove (detach) product specific variation from (product_variation_cart) pivot table
    public function remove(ProductVariation $variation): void
    {
        $this->instance()->variations()->detach($variation);
    }

    // forget cart session and delete cart instance from the carts table
    public function destroy(): void
    {
        $this->session->forget(config('cart.session.key'));
        $this->instance()->delete();
    }

    // total amount in cart
    public function subtotal(): int
    {
        $total = $this->instance()->variations->reduce(function($acc, $curr) {
            $quantity = $curr->pivot->quantity;
            // if variation doesn't have price(or equal to zero or null) take product price
            $price = !$curr->price ? $curr->product->price : $curr->price;

            $acc += $price * $quantity;

            return $acc ;
        });

        return $total;
    }

    public function formattedSubtotal()
    {
        return money($this->subtotal());
    }

    public function verifyAvailableStockQuantities(): void
    {
        $this->contents()->each(function($variation) {
            if($variation->pivot->quantity > $variation->stocks->sum('amount')) {
                throw new StockQuantityReduced('stock reduced. quantity no longer available');
            }
        });
    }

    public function syncAvailableQuantity(): void
    {
        // e.g array [
        //      'variation_id' => ['quantity' => $quantityToSync]
        // ]
        $arrayToSync = $this->contents()->mapWithKeys(function($variation) {
            // if the product variation quantity in the cart is greater than total stock
            // reduce the quantity in the cart to maximun stock amount
            $quantity = $variation->pivot->quantity > $variation->stocks->sum('amount')
                                        ? $variation->stockCount() :
                                        $variation->pivot->quantity;

            return [
                $variation->id => [
                    'quantity' => $quantity,
                ]
            ];
        })
        // remove from the cart if out of stock
        ->reject(function($synced) {
            return $synced['quantity'] === 0;
        })
        ->toArray();

        // sync
        $this->instance()->variations()->sync($arrayToSync);

        $this->clearCartInstance();
    }

    // clear all product variations from pivot table
    public function clear(): void
    {
        $this->instance()->variations()->detach();
    }

    public function clearCartInstance()
    {
        $this->instance = null;
    }

    // check payment intent for stripe payment stored in carts table
    public function hasPaymentIntent(): bool
    {
        return !is_null($this->getPaymentIntentId());
    }

    // get payment_intent_id for stripe payment stored in carts table
    public function getPaymentIntentId(): ?string
    {
        return $this->instance()->payment_intent_id;
    }

    // update payment_intent_id for stripe payment stored in carts table
    public function updatePaymentIntentId(string $paymentIntentId): void
    {
        $this->instance()->update([
            'payment_intent_id' => $paymentIntentId,
        ]);
    }

}
