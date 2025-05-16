<?php

namespace App\Responses;

use App\Http\Resources\UserContestResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

class StudentStaticsResponse
{
    public static function response(User $user): JsonResponse
    {

        return response()->json([
            'status' => true,
            'completed_courses' => $user->finishedCourses()->count(),
            'certificates' => $user->certificates()->count(),
            'contests' => $user->contests()->count(),
            'points' => $user->points,
            'achievements' => $user->achievements()->count(),
            'best_contest' => new UserContestResource($user->contests()->orderByPivot('rank', 'desc')->without('pivot')->first()),
            'max_streak' => $user->LongestStreak(),
            'current_streak' => $user->CurrentStreak(),
        ]);
    }
}
