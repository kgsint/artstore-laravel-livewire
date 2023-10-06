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
        {{-- category title --}}
        <h3 class="text-gray-700 text-2xl font-medium mt-6">{{ $category->title }}</h3>
        {{-- no of product --}}
        <span class="mt-3 text-sm text-gray-500">{{ $category->products->count() }} {{ Str::plural('product', count($category->products)) }}</span>
        <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 mt-6">
            {{-- loop related products and display --}}
            @foreach ($category->products as $product)
                <a href="{{ route('products.show', $product->slug) }}" wire:navigate class="block w-full max-w-sm mx-auto rounded-md shadow-md overflow-hidden">
                    <div class="flex items-end justify-end h-56 w-full bg-cover" style="background-image: url('{{ $product->getMedia()->first()->getUrl() }}')">
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
