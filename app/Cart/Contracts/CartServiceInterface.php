<?php

namespace App\Cart\Contracts;

use App\Models\ProductVariation;
use App\Models\User;
use Staudenmeir\LaravelAdjacencyList\Eloquent\Collection;

interface CartServiceInterface
{
    // create record(row) in carts table in the database
    public function create(?User $user): void;

    public function associate(User $user): void;

    // check if the cart related session exists or not
    public function exists(): bool;

    // product variation(s) related to user's cart
    public function contents(): Collection;

    // no of product variation(s) in user's cart
    public function contentCount(): int;

    // check if the cart is empty or not
    public function isEmpty(): bool;

    // add product to cart (i.e add record to cart_product_variation table)
    public function add(ProductVariation $variation): void;

    public function changeQuantity(ProductVariation $variation, int $quantity): void;

    // remove cart item
    public function remove(ProductVariation $variation): void;

    // total amount in cart
    public function subtotal(): int;

    // formatted total amount
    public function formattedSubtotal();

    // verify each quantity(ies) for product variation in the cart is available in stock
    public function verifyAvailableStockQuantities(): void;
    // sync available stock amount with product_variation_cart pivot table or (user's chosen quantity in cart)
    public function syncAvailableQuantity(): void;

    // clear all items from the cart
    public function clear(): void;

    // forget cart session and delete cart instance from the carts table
    public function destroy(): void;

    // check payment intent for stripe payment stored in carts table
    public function hasPaymentIntent(): bool;

    // get payment_intent_id for stripe payment stored in carts table
    public function getPaymentIntentId(): ?string;

    // update or change payment_intent_id for stripe payment stored in carts table
    public function updatePaymentIntentId(string $paymentIntentId): void;
}
