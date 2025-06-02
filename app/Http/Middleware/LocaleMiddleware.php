<?php

namespace App\Http\Middleware;

use App\Services\User\StreakService;
use Closure;

class LocaleMiddleware
{
    protected StreakService $streakService;
    public function __construct(StreakService $streakService)
    {
        $this->streakService = $streakService;
    }

    public function handle($request, Closure $next)
    {


        $user = \Auth::user();
        if ($user) {
            $user->last_online = now()->toDateTimeString();
            $user->save();
            $this->streakService->LoginStreak($user);
        }


        return $next($request);

    }
}
