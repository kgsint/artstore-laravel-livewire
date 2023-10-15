<div class="container mx-auto p-6 max-w-6xl shadow">
    <h3 class="text-gray-700 text-2xl font-medium border-b border-gray-300 p-3">Your Cart</h3>
    <div class="flex flex-col lg:flex-row mt-8 space-x-3 space-y-6">
        <div class="w-full lg:w-1/2">
            @forelse ($cart->contents() as $variation)
                <livewire:cart-item :variation="$variation" :key="$variation->id" />
                @empty
                    <div class="text-center">There is no item at the moment.</div>
            @endforelse
        </div>

        @if ($cart->contentCount())
            <div class="w-full mb-8 flex-shrink-0 lg:w-1/2 lg:mb-0 border-l">
                <div class="flex">
                    <div class=" rounded-md max-w-md w-full px-4 py-3">
                        <div>
                            {{-- <h3 class="text-gray-700 text-xl font-semibold mb-3">Cart Summary</h3> --}}

                            <div class="flex justify-between mb-6">
                                <h5 class="text-lg font-semibold">Subtotal: </h5>
                                <span>{{ $cart->formattedSubtotal() }}</span>
                            </div>

                            <a href="/checkout" wire:navigate>
                                <x-secondary-button class="mt-auto float-right">
                                    Checkout
                                </x-secondary-button>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
