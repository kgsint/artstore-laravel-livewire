@extends('layouts.app')

@section('content')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-md space-y-3">
                {{-- single order --}}
                @forelse ($orders as $order)

                <div class="bg-white p-6 col-span-4 space-y-3">
                    <div class="border-b pb-3 flex items-center justify-between">
                        <h4>#{{ $order->id }}</h4>
                        <h4>{{ $order->formattedSubtotal() }}</h4>
                        <h4>{{ $order->shippingType->title }}</h4>
                        <h4>{{ $order->created_at->format('d/M/Y h:i a') }}</h4>

                        <div>
                            <span class="inline-flex items-cente px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-800">
                                {{ $order->presenter()->present() }}
                            </span>
                        </div>
                    </div>

                    @foreach ($order->variations as $variation)
                        <div class= "border-b py-3 flex items-center last:border-0 last:pb-0">
                            <div class="w-16 mr-4">
                                <img src="{{ $variation->parent->getFirstMediaUrl('default') ?: $variation->product->getFirstMediaUrl('default') }}" class="w-full">
                            </div>

                            <div class="space-y-3">
                                <div class="">
                                    <h4 class="font-semibold">{{ $variation->formattedPrice() }}</h4>
                                    <h4 class="font-semibold">{{ $variation->product->title }}</h4>
                                </div>

                                <div class="text-sm">
                                    <div class="font-semibold">
                                        Quantity: {{ $variation->pivot->quantity }}
                                        <span class="text-gray-400 mx-1">

                                        </span>
                                    </div>

                                    <div>
                                        @foreach ($variation->ancestorsAndSelf as $ancestor)

                                            <div class="font-semibold">
                                                <span>{{ $ancestor->type }}</span> : <span>{{ $ancestor->title }}</span>
                                            </div>

                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @empty
                    No order at the moment
                @endforelse

            </div>
        </div>
    </div>

@endsection
