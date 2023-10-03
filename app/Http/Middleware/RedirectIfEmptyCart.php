<?php

namespace App\Http\Middleware;

use App\Cart\Contracts\CartServiceInterface;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfEmptyCart
{
    public function __construct(
        private CartServiceInterface $cart,
    ){}

    public function handle(Request $request, Closure $next): Response
    {
        if($this->cart->isEmpty()) {
            return redirect()->route('cart.index');
        }

        return $next($request);
    }
}
