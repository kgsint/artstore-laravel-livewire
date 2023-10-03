<div>
    @if ($initialVariations)
        <livewire:product-dropdown :variations="$initialVariations" />
        @if ($skuVariation)
            <div class="mt-6">
                <h5 class="text-lg font-semibold">
                    {{ $skuVariation->price === 0 ?
                                                            $skuVariation->product->formattedPrice() :
                                                            $skuVariation->formattedPrice()
                    }}
                </h5>
                <x-primary-button wire:click="addToCart" class="mt-3">Add to Cart</x-primary-button>
            </div>
        @endif
    @endif
</div>
