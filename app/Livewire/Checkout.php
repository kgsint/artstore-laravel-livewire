<?php

namespace App\Livewire;

use App\Cart\Contracts\CartServiceInterface;
use App\Livewire\Forms\CheckoutAccountForm;
use App\Livewire\Forms\CheckoutShippingAddressForm;
use App\Mail\OrderCreated;
use App\Mail\OrderStatusCreated;
use App\Models\Order;
use App\Models\ShippingAddress;
use App\Models\ShippingType;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Checkout extends Component
{
    public $shippingTypes;
    public $shippingAddress;

    // wire:model for presaved shipping address select
    public $presavedShippingAddress;

    #[Rule('required', as: 'delivery service', message: 'Please choose the delivery service')]
    public $shippingTypeId;

    // account form for guest users
    public CheckoutAccountForm $accountForm;
    // shipping and address form
    public CheckoutShippingAddressForm $shippingForm;

    public function mount()
    {
        // set shipping types
        $this->shippingTypes = ShippingType::orderBy('title', 'asc')->get();

        // prepopulate email address if the user is signed in
        if($user = auth()->user()) {
            $this->accountForm->email = $user->email;
        }
    }

    // dynamic getter for displaying shipping type in final checkout
    public function getShippingTypeProperty()
    {
        return $this->shippingTypes->find($this->shippingTypeId) ?? null;
    }

    // dynamic getter for total (subtotal + shipping price)
    public function getTotalProperty(CartServiceInterface $cart)
    {
        return $cart->subtotal() + ($this->shippingType?->price ?? 0);
    }

    // optional dynamic getter for previous shipping address(es) to prepopulate $shippingForm
    public function getShippingAddressesProperty()
    {
        return auth()->user()?->shippingAddresses;
    }

    // watch for changes in pre-saved shipping address select and prepopulate shipping form
    public function updatedPresavedShippingAddress($id)
    {
        // reset
        if(!$id) {
            $this->shippingForm->address = '';
            $this->shippingForm->city = '';
            $this->shippingForm->postcode = '';

            return;
        };

        // populate
        $this->shippingForm->populate($this->shippingAddresses->find((int) $id));

    }

    // call livewire validate from alpine js
    public function callValidate()
    {
        $this->validate();
    }

    public function getErrorCount()
    {
        return $this->getErrorBag()->count();
    }

    // submit
    public function checkout(CartServiceInterface $cart)
    {
        $this->validate();

        // dd('ok');

        if($this->getPaymentIntent($cart)->status !== 'succeeded') {
            $this->dispatch('notification', [
                'body' => 'Payment error',
            ]);
            return;
        }

        // shipping address query builder
        $this->shippingAddress = ShippingAddress::query();

        // if authenticated, check if the address already exists or not
        if($user = auth()->user()) {
            $this->shippingAddress = $this->shippingAddress->whereBelongsTo($user);
        }

        // saving address | create if doens't exist  and associate if authenticated and assign shippingAddress
        ($this->shippingAddress = $this->shippingAddress->firstOrCreate($this->shippingForm->all()))
            ?->user()
            ->associate(auth()->user())
            ->save();

        // save order into database and associate with respective relationships (user, shippingType & shippingAddress)
        $order = Order::make([
            'email' => $this->accountForm->email,
            'subtotal' => $this->total, // getter
        ]);

        $order?->user()->associate(auth()->user());
        $order->shippingAddress()->associate($this->shippingAddress);
        $order->shippingType()->associate(ShippingType::find($this->shippingTypeId));

        $order->save();

        // attach with order_product_variation pivot table
        $orderedVariationsArray = $cart->contents()->mapWithKeys(
            fn($variation) => [
                $variation->id => [
                    'quantity' => $variation->pivot->quantity,
                ]
            ])->toArray();

        $order->variations()->attach($orderedVariationsArray);

        // reduce stock for respective product variation after ordered
        $cart->contents()->each(function($variation) {
            $variation->stocks()->create([
                'amount' => 0 - $variation->pivot->quantity
            ]);
        });

        // clear items in the cart
        $cart->clear();
        // destroy cart_session and cart instance in carts table
        $cart->destroy();

        // mail to user when create order
        // Mail::to($order?->user ?? $order->email)->send(new OrderCreated);

        $cart->destroy();

        if(auth()->user()) {
            return redirect()->route('orders.index');
        }else {
            return redirect()->route('order.confirm', $order->uuid);
        }
    }

    public function getPaymentIntent(CartServiceInterface $cart)
    {
        if(! $cart->hasPaymentIntent()) {
            // set stripe payment intent if payment_intent_id not exists (null) in related cart model
            $paymentIntent = app('stripe')->paymentIntents->create([
                'amount' => $this->total,
                'currency' => 'usd',
                'setup_future_usage' => 'on_session',
            ]);

            //  store or update in carts table
            $cart->updatePaymentIntentId($paymentIntent->id);

        }

        // get payment intent if already exists
        $paymentIntent = app('stripe')->paymentIntents->retrieve($cart->getPaymentIntentId());

        if($paymentIntent->status !== 'succeeded') {
            // update amount in payment intent if not succeeded status
            $paymentIntent = app('stripe')->paymentIntents->update($cart->getPaymentIntentId(), [
                'amount' => $this->total,
            ]);
        }

        return $paymentIntent;
    }

    public function render(CartServiceInterface $cart)
    {
        $paymentIntent = $this->getPaymentIntent($cart);

        // dd($paymentIntent);

        return view('livewire.checkout', compact('cart', 'paymentIntent'));
    }
}
