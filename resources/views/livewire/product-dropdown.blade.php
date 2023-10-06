<div class="space-y-3">
    <div>
        {{ Str::title($variations->first()->type) }}
    </div>

    {{-- select product variation --}}
    <x-select wire:model.live="selectedVariation">
        <option value="">Choose {{ $variations->first()->type }}</option>
        @foreach ($variations as $variation)
            <option value="{{ $variation->id }}" {{ $variation->isOutOfStock() ? 'disabled' : '' }}>
                {{ $variation->title }} ({{ $variation->stockCount() }}) {{ $variation->isLowStock() ? '(low stock)' : '' }} {{ $variation->isOutOfStock() ? 'out of stock' : '' }}
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
