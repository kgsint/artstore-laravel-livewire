<?php

namespace App\Providers;

use App\Contracts\ProductInterface;
use App\Services\ProductService;
use Stripe\StripeClient;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // register product service for product interface
        $this->app->bind(ProductInterface::class, fn() => new ProductService);

        // register stripe
        $this->app->singleton('stripe', function() {
            return new StripeClient(config('stripe.secret'));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(UrlGenerator $urlGenerator): void
    {
        if($this->app->environment('APP_ENV') === 'production') {
            $urlGenerator->forceScheme('https');
        }
    }
}
