<div class="space-y-3">
    <div>
        {{ Str::title($variations->first()->type) }}
    </div>

    {{-- select product variation --}}
    <x-select wire:model.live="selectedVariation">
        <option value="">Choose {{ $variations->first()->type }}</option>
        @foreach ($variations as $variation)
            <option value="{{ $variation->id }}" {{ $variation->isOutOfStock() ? 'disabled' : '' }}>
                {{ $variation->title }}
                <span class="text-xs text-gray-200">
                    ({{ $variation->stockCount() }} {{ Str::plural('item', $variation->stockCount()) }}
                </span>)
                {{ $variation->isLowStock() ? '(low stock)' : '' }} {{ $variation->isOutOfStock() ? 'out of stock' : '' }}
            </option>
        @endforeach
    </x-select>

    {{-- recursively loop --}}
    @if ($this->selectedVariationModel?->children->count())
        <livewire:product-dropdown
            :variations="$this->selectedVariationModel?->children->sortBy('order')"
            :key="$selectedVariation" />
    @endif

</div>
