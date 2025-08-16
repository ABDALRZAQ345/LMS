<?php

namespace App\Http\Middleware;

use App\Services\AchievementsService;
use App\Services\User\StreakService;
use Closure;

class LocaleMiddleware
{
    protected StreakService $streakService;
    protected  AchievementsService $achievementsService;
    public function __construct(StreakService $streakService, AchievementsService $achievementsService)
    {
        $this->streakService = $streakService;
        $this->achievementsService = $achievementsService;
    }

    public function handle($request, Closure $next)
    {


        $user = auth('api')->user();
        if ($user) {
            if($user->last_online && $user->last_online <  now()->subYear()->toDateTimeString()){
                $this->achievementsService->ReturnAfterYear($user);
            }
            $user->last_online = now()->toDateTimeString();
            $user->save();
            if($user->role=='student')
            $this->streakService->LoginStreak($user);

        }


        return $next($request);

    }
}
