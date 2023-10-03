<div class="space-y-4">
    <img src="{{ $selectedImageUrl }}" alt="">

    <div class="grid grid-cols-6 gap-2">
        <button class="cursor-pointer" wire:click="selectThumbnail('{{ $product->getFirstMediaUrl() }}')">
            <img
                src="{{ $product->getFirstMediaUrl() }}"
                    class="border p-2 rounded-md
                    {{ $selectedImageUrl === $product->getFirstMediaUrl() ? 'border-blue-400' : ''}}">
        </button>
        @foreach ($product->variations as $variation)
        {{-- {{ $variation->getMediaUrl('thumbnail') }} --}}
            @if ($variation->getFirstMediaUrl())
                <button class="cursor-pointer" wire:click="selectThumbnail('{{ $variation->getFirstMediaUrl() }}')">
                    <img
                        src="{{ $variation->getFirstMediaUrl() }}"
                            class="border p-2 rounded-md
                            {{ $selectedImageUrl === $variation->getFirstMediaUrl() ? 'border-blue-400' : ''}}">
                </button>
            @endif
        @endforeach
    </div>
</div>
