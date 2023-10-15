<div class="flex justify-between mt-6">
    <div class="flex">
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
            <div
                x-data="{count: 1, stocks: @js($variation->stockCount())}"
                x-modelable="count" class="flex items-center mt-2"
                wire:model.live="quantity">
                {{-- decrease quantity --}}
                <button @click="count > 1 ? count-- : ''" class="text-gray-500 focus:outline-none focus:text-gray-600">
                    <svg class="h-5 w-5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor"><path d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </button>
                {{-- show quantity --}}
                <span class="text-gray-700 mx-2" x-text="count"></span>
                {{-- increase quantity --}}
                <button @click="count < stocks ? count++ : ''" class="text-gray-500 focus:outline-none focus:text-gray-600">
                    <svg class="h-5 w-5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </button>
            </div>

        </div>
    </div>

    <div class="flex flex-col space-y-3">
        <span class="text-gray-600">
            {{ $variation->price ? $variation->formattedPrice() : $variation->product->formattedPrice() }}
        </span>
        <span class="text-sm text-red-500 cursor-pointer" wire:click="removeCartItem">
            Remove
        </span>
        {{-- price for product variation or parent product --}}
    </div>
</div>
