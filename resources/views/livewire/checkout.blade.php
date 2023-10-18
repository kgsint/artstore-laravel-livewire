<form
    @submit.prevent="submit"
    x-data="
    {
        stripe: null,
        cardElement: null,
        clientEmail: @entangle('accountForm.email'),

        async submit() {
            await $wire.callValidate()

            const errorCount = await $wire.getErrorCount()

            if(errorCount > 0) {
                return
            }

            // process card payment
            const { paymentIntent, error } = await this.stripe.confirmCardPayment('{{ $paymentIntent->client_secret }}', {
                payment_method: {
                    card: this.cardElement,
                    billing_details: { email: this.clientEmail },
                }
            })

            if(error) {
                window.dispatchEvent(new CustomEvent('notification', {
                    detail: {
                        body: error.message
                    }
                }))
            }else {
                $wire.checkout()
            }
        },

        init() {
            this.stripe = Stripe('{{ config('stripe.key') }}')

            const elements = this.stripe.elements()
            this.cardElement = elements.create('card')

            this.cardElement.mount('#card-element')
        }
    }
    "
>

    <div class="overflow-hidden sm:rounded-lg grid grid-cols-6 grid-flow-col gap-4">
        <div class="p-6 bg-white border-b border-gray-200 col-span-6 md:col-span-3 self-start space-y-6">
            <div class="space-y-3">
                <h3 class="font-semibold">Account Details</h3>
            </div>

            {{-- account form if unauthenticated --}}
            @guest
            {{-- {{ print_r($accountForm) }} --}}
                <div>
                    <x-input-label for="Email" value="Email" class="mb-2" />
                    <x-text-input type="text" name="email" class="block w-full" wire:model="accountForm.email" />

                    @error('accountForm.email')
                        <x-input-error :message="$message" />
                    @enderror
                </div>

            @endguest

            {{-- pre-saved shipping address if there is any --}}
            @if (count($this->shippingAddresses) ?? false)
                <div>
                    <x-input-label for="Shipping" value="Shipping" class="mb-2" />
                    <x-select class="block w-full" name="shipping" wire:model.live="presavedShippingAddress">
                        <option value="">Choose a pre-saved address</option>

                        @foreach ($this->shippingAddresses as $address)
                            <option value="{{ $address->id }}">{{ $address->formattedAddress() }}</option>
                        @endforeach
                    </x-select>
                </div>
            @endif

            <div>
                <x-input-label for="Address" value="Address" class="mb-2" />
                <x-text-input type="text" class="block w-full" wire:model="shippingForm.address" value="{{ $shippingForm?->city ?? '' }}" />

                {{-- error message --}}
                @error('shippingForm.address')
                    <x-input-error :message="$message" />
                @enderror
            </div>

            <div class="flex justify-between">
                <div class="w-7xl">
                    <x-input-label for="City" value="City" class="mb-2" />
                    <x-text-input type="text" class="" wire:model.live="shippingForm.city" value="{{ $shippingForm?->address ?? '' }}" />

                    {{-- error message --}}
                    @error('shippingForm.city')
                        <x-input-error :message="$message" />
                    @enderror
                </div>

                <div class="w-7xl">
                    <x-input-label for="Postal Code" value="Postal Code" class="mb-2" />
                    <x-text-input type="text" wire:model.live="shippingForm.postcode" value="{{ $shippingForm?->postcode ?? '' }}"  />

                    {{-- error message --}}
                    @error('shippingForm.postcode')
                        <x-input-error :message="$message" />
                    @enderror
                </div>
            </div>

            <div>
                <x-select class="block w-full" wire:model.live="shippingTypeId">

                    <option value="">Choose a delivery service</option>

                    @foreach ($shippingTypes as $shipping)
                        <option value="{{ $shipping->id }}"> {{ $shipping->title }} ({{ $shipping->formattedPrice() }}) </option>
                    @endforeach
                </x-select>

                {{-- error message --}}
                @error('shippingTypeId')
                    <x-input-error :message="$message" />
                @enderror
            </div>

            {{-- stripe form --}}
            <div>
                {{-- <x-input-label value="{{ $paymentIntent->client_secret }}" /> --}}
                <div wire:ignore id="card-element"></div>
            </div>
        </div>

        <div class="bg-white col-span-6 md:col-span-3 p-6">
            <div class="border-b space-y-6">
                    @foreach ($cart->contents() as $variation)
                    <div class="flex justify-between mt-6  border-b last:border-b-0">
                        <div class="flex mb-3">
                            {{-- image --}}
                            <img
                                class="h-20 w-20 object-cover rounded"
                                src="{{ $variation?->parent?->getFirstMediaUrl('default') ?: $variation->product->getFirstMediaUrl('default') }}" alt="product image"
                            >
                            <div class="mx-3">
                                {{-- product title --}}
                                <h3 class="text-lg text-black">{{ $variation->product->title }}</h3>
                                {{-- variation wrapper --}}
                                <div>
                                    {{-- displaying variations and children if exists --}}
                                    @foreach ($variation->ancestorsAndSelf as $v)
                                        <div class="text-sm text-gray-600">
                                            {{ $v->type }} : {{ $v->title }}
                                        </div>
                                    @endforeach
                                </div>
                                {{-- quantity wrapper --}}
                                <div class="text-sm text-gray-600">
                                    quantity: {{ $variation->pivot->quantity }}
                                </div>

                            </div>
                        </div>

                        <div class="flex flex-col space-y-3">
                            <span class="text-gray-600">
                                {{ $variation->price ? $variation->formattedPrice() : $variation->product->formattedPrice() }}
                            </span>
                            {{-- price for product variation or parent product --}}
                        </div>
                    </div>
                    @endforeach
                </div>

            <div class="flex justify-between p-3">
                <h3 class="font-semibold">Subtotal</h3>
                <p>{{ $cart->formattedSubtotal() }}</p>
            </div>

            <div class="flex justify-between p-3">
                <h3 class="font-semibold">Shipping

                </h3>
                <p>{{ $this->shippingType?->formattedPrice() }}</p>

            </div>

            <div class="flex justify-between p-3">
                <h3 class="font-semibold">Total</h3>
                <p>{{ money($this->total) }}</p>
            </div>

            <button
                wire:loading.class = "opacity-50"
                class="w-full justify-center inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
            >
                <span wire:loading.remove>
                    Confirm Order
                </span>

                <span wire:loading>
                    Loading
                </span>
            </button>


        </div>
    </div>

</form>

<script>
    // stripe js
    // document.addEventListener('alpine:init', () => {
    //     Alpine.data('stripe', () => ({
    //         stripe: null,
    //         cardElement: null,

    //         async submit() {
    //             await $wire.callValidate()
    //         },

    //         init() {
    //             this.stripe = Stripe('{{ config('stripe.key') }}')

    //             const elements = this.stripe.elements()
    //             this.cardElement = elements.create('card')

    //             this.cardElement.mount('#card-element')
    //         }
    //     }))
    // })
</script>
