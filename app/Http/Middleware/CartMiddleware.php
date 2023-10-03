<?php

namespace App\Http\Middleware;

use App\Cart\Contracts\CartServiceInterface;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CartMiddleware
{
    public function __construct(
        private readonly CartServiceInterface $cart,
    ){}

    public function handle(Request $request, Closure $next): Response
    {
        if(!$this->cart->exists()) {
            $this->cart->create($request->user());
        }

        return $next($request);
    }
}
