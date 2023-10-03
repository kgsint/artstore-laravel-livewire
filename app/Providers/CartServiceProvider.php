<?php

namespace App\Providers;

use App\Cart\CartService;
use App\Cart\Contracts\CartServiceInterface;
use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(CartServiceInterface::class, fn() => new CartService(session()));
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
