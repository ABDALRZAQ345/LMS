<?php

namespace App\Http\Middleware;

use App\Exceptions\NotFoundException;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsStudent
{
    /**
     * @throws NotFoundException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->route('user');

        if (! $user instanceof User || $user->role !== 'student') {
            throw new NotFoundException;
        }

        return $next($request);
    }
}
