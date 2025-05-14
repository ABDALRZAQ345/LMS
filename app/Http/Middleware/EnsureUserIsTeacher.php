<?php

namespace App\Http\Middleware;

use App\Exceptions\NotFoundException;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsTeacher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->route('user');

        if (! $user instanceof User || $user->role !== 'teacher') {
            throw new NotFoundException;
        }

        return $next($request);
    }
}
