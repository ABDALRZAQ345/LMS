<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContestTypeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $type): Response
    {
        $contest = $request->route('contest');
        if ($contest->type == $type) {
            return $next($request);
        } else {
            return \response()->json([
                'status' => false,
                'message' => "Contest Type must be $type",
            ]);
        }
    }
}
