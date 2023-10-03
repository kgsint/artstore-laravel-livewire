<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        {{-- stripe js --}}
        <script src="https://js.stripe.com/v3/"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-notification />

        <div x-data="{ cartOpen: false , isOpen: false }">
            <livewire:navigation />

            @include('layouts.cart')

            <main class="my-8 min-h-screen mb-auto">
                @yield('content')
            </main>

            @include('layouts.footer')
        </div>

        @livewireScripts
    </body>
</html>
