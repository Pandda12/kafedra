<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmployeeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle( Request $request, Closure $next ): Response
    {
        $user = $request->user();

        if ( !$user || !$user->isEmployee() || !$user->is_active || (int)$user->auth_step !== 2 ) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
