<div class="grid grid-cols-1 xl:grid-cols-12 xl:space-x-12 max-w-7xl mx-auto">
    @php
        // categories for filter dropdown (seperate from product category -  when the component rerender, the dropdown lists got filtered as well)
        $categoriesForFilter = \App\Models\Category::query()
                                ->select('categories.*')
                                ->with('products.media')
                                ->rightJoin('category_product', 'categories.id', 'category_product.category_id')
                                ->where('categories.parent_id', null)
                                ->groupBy('categories.id')
                                ->orderBy('categories.title', 'asc')
                                ->get()
    @endphp

    <div class="xl:col-span-2 mt-6 mx-auto">
        <label for="">Filter by Category</label>
        <x-select wire:model.live="filter" class="w-full">
            <option value="">All</option>
            @foreach ($categoriesForFilter as $category)
                <option value="{{ $category->slug }}">{{ $category->title }}</option>
            @endforeach
        </x-select>
    </div>

    <div class="w-full xl:col-span-10 mx-auto max-w-7xl">
        @foreach ($categories as $category)
        <h3 class="text-gray-700 text-2xl font-medium mt-6">{{ $category->title }}</h3>
        <span class="mt-3 text-sm text-gray-500">{{ $category->products->count() }} Products</span>
        <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 mt-6">
            @foreach ($category->products as $product)
                <a href="{{ route('products.show', $product->slug) }}" class="block w-full max-w-sm mx-auto rounded-md shadow-md overflow-hidden">
                    <div class="flex items-end justify-end h-56 w-full bg-cover" style="background-image: url('{{ $product->getMedia()->first()->getUrl() }}')">
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
        </div>
        @endforeach
    </div>

    {{-- <div class="flex justify-center">
        <div class="flex rounded-md mt-8">
            <a href="#" class="py-2 px-4 leading-tight bg-white border border-gray-200 text-blue-700 border-r-0 ml-0 rounded-l hover:bg-blue-500 hover:text-white"><span>Previous</a></a>
            <a href="#" class="py-2 px-4 leading-tight bg-white border border-gray-200 text-blue-700 border-r-0 hover:bg-blue-500 hover:text-white"><span>1</span></a>
            <a href="#" class="py-2 px-4 leading-tight bg-white border border-gray-200 text-blue-700 border-r-0 hover:bg-blue-500 hover:text-white"><span>2</span></a>
            <a href="#" class="py-2 px-4 leading-tight bg-white border border-gray-200 text-blue-700 border-r-0 hover:bg-blue-500 hover:text-white"><span>3</span></a>
            <a href="#" class="py-2 px-4 leading-tight bg-white border border-gray-200 text-blue-700 rounded-r hover:bg-blue-500 hover:text-white"><span>Next</span></a>
        </div>
    </div> --}}
</div>
