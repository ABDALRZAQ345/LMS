<?php

namespace App\Http\Middleware;

use App\Exceptions\UNAuthorizedException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     *
     * @throws UNAuthorizedException
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = \Auth::user();
        if (! $user || $user->role !== $role) {
            throw new UNAuthorizedException;
        }

        return $next($request);
    }
}
