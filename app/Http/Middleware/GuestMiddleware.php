<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GuestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ( auth()->check() ) {
            if ( auth()->user()->isAdmin() ) {
                return redirect()->intended(route('dashboard', absolute: false));
            } else {
                return redirect()->intended(route('home'));
            }
        }

        return $next($request);
    }
}
