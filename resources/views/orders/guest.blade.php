@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm rounded-md">
        <h3 class="p-6 border-b border-gray-200">
            Your order (#{{ $order->uuid }}) has been placed.

            <a href="/register" class="text-bold underline">
                Create an account
            </a>
            to manage your orders.

        </h3>
    </div>
</div>
@endsection
