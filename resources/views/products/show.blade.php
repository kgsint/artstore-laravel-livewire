@extends('layouts.app')


@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto px-6 md:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200 grid grid-cols-3 gap-4">
                <div class="col-span-1 grid">
                    <livewire:product-thumbnail :product="$product" />
                </div>
                <div class="col-span-2 p-6 space-y-6">
                    <div>
                        <h1>{{ $product->title }}</h1>
                        <h2 class="font-semibold text-xl mt-2">
                            {{ $product->formattedPrice() }}
                        </h2>

                        <p class="mt-2 text-gray-500">
                            {{ $product->description }}
                        </p>
                    </div>

                    <div class="mt-6">
                        {{-- {{ $product->variations->sortBy('order')->groupBy('type')->first() }} --}}
                        <livewire:product-selector :product="$product" />
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
