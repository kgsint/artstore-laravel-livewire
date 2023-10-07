<header class="bg-white">
    <div class="container mx-auto px-6 py-3">
        <div class="flex items-center justify-between">
            <div class="hidden w-full text-black md:flex md:items-center text-4xl font-bold" style="font-family: Caveat">
                <a href="/" wire:navigate>
                    kgsint store
                </a>
            </div>
            <div class="w-full text-black md:text-center text-2xl uppercase" style="font-family: Caveat">
                Art Supplies
            </div>
            <div class="flex items-center justify-end space-x-3 w-full">
                <a href="/cart" wire:navigate class="flex items-center text-gray-600 focus:outline-none mx-4 sm:mx-0 hover:text-black">
                    <svg class="h-5 w-5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    ({{ $this->cart->contentCount() }})
                </a>

                @guest
                    <a href="{{ route('login') }}" wire:navigate>Login</a>
                    <a href="{{ route('register') }}" wire:navigate>Register</a>
                    @else
                    <button form="logoutForm">Logout</button>

                    <form id="logoutForm" action="{{ route('logout') }}" method="POST">
                        @csrf
                    </form>
                @endguest

                <div class="flex sm:hidden">
                    <button @click="isOpen = !isOpen" type="button" class="text-gray-600 hover:text-gray-500 focus:outline-none focus:text-gray-500" aria-label="toggle menu">
                        <svg viewBox="0 0 24 24" class="h-6 w-6 fill-current">
                            <path fill-rule="evenodd" d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <nav :class="isOpen ? '' : 'hidden'" class="sm:flex sm:justify-center sm:items-center mt-4">
            <div class="flex flex-col sm:flex-row">
                <a class="mt-3 text-gray-600 hover:underline sm:mx-3 sm:mt-0" href="/products" wire:navigate>Shop</a>
                <a class="mt-3 text-gray-600 hover:underline sm:mx-3 sm:mt-0" href="/categories" wire:navigate>Categories</a>
                {{-- <a class="mt-3 text-gray-600 hover:underline sm:mx-3 sm:mt-0" href="#">Contact</a> --}}
                @auth
                    <a class="mt-3 text-gray-600 hover:underline sm:mx-3 sm:mt-0" href="/orders" wire:navigate>Orders</a>
                @endauth
            </div>
        </nav>
        <div class="relative mt-6 max-w-lg mx-auto">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                <svg class="h-5 w-5 text-gray-500" viewBox="0 0 24 24" fill="none">
                    <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>

            {{-- search input --}}
                <input
                    class=" pl-10 block w-full rounded-md border-gray-300 shadow-sm -z-10 focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                    wire:model.live.debounce.150ms="search"
                    type="text"
                    placeholder="Search"
                >

                {{-- global search dropdown --}}
                @if ($search && count($this->products))
                    <div class="absolute right-0  bg-gray-100 shadow rounded-md w-full space-y-3">
                            @foreach ($this->products as $product)
                                <a href="/products/{{ $product->slug }}" wire:navigate class="flex gap-3 hover:bg-gray-200  p-3">
                                    <img src="{{ $product->getFirstMediaUrl() }}" alt="{{ $product->title }} image" class="w-8 h-8">
                                    <div>
                                        <h3>{{ $product->title }}</h3>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @elseif($search && !count($this->products))
                        <div class="absolute right-0  bg-gray-100 shadow rounded-md w-full space-y-3">
                            <div class="flex gap-3 hover:bg-gray-200  p-3">
                                <h3>No Product Found</h3>
                            </div>
                        </div>
                @endif
            </div>

    </div>
</header>
