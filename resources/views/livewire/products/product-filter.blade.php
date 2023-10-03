<section class="grid grid-cols-1 xl:grid-cols-12 space-x-12 max-w-7xl mx-auto">
    {{-- filter products with variations --}}
    <div class="xl:col-span-2 p-6  lg:mx-auto">
        <h3 class="mb-6 border-b border-gray-900">Filter product</h3>
        @foreach ($uniqueVariations as $type => $titles)
           <div class="border-b last:border-b-0 mb-3 pb-3">
                <h2>{{ Str::title($type) }}</h2>
                @foreach ($titles as $title)
                    <div>
                        <input type="checkbox" wire:model.live="filters" value="{{ $title }}" id="filters-{{ $title }}" class="cursor-pointer">
                        <label for="filters-{{ $title }}" class="text-sm text-gray-700 cursor-pointer">{{ $title }}</label>
                    </div>
                @endforeach
           </div>
        @endforeach

    </div>
    <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 mt-6 max-w-7xl mx-auto xl:col-span-10">
        @foreach ($products as $product)
            <a href="{{ route('products.show', $product->slug) }}" class="block w-full max-w-sm mx-auto rounded-md shadow-md overflow-hidden">
                <div class="flex items-end justify-end h-56 w-full bg-cover" style="background-image: url('{{ $product->getFirstMediaUrl() }}')">
                    {{-- <button class="p-2 rounded-full bg-blue-600 text-white mx-5 -mb-4 hover:bg-blue-500 focus:outline-none focus:bg-blue-500">
                        <svg class="h-5 w-5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </button> --}}
                </div>
                <div class="px-5 py-3">
                    <h3 class="text-gray-700 uppercase">{{ $product->title }}</h3>
                    <span class="text-gray-500 mt-2">{{ $product->formattedPrice() }}</span>
                </div>
            </a>
        @endforeach

        <div class="xl:col-span-4 flex justify-center mx-auto max-w-sm">
            {{ $products->links() }}
        </div>
    </div>
</section>
