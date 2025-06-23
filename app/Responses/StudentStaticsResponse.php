<?php

namespace App\Responses;

use App\Http\Resources\Users\UserContestResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class StudentStaticsResponse
{
    public static function response(User $user): JsonResponse
    {
    $bestContest=$user->contests()->orderByPivot('rank', 'desc')->without('pivot')->first();
        return response()->json([
            'status' => true,
            'completed_courses' => $user->finishedCourses()->count(),
            'certificates' => $user->certificates()->count(),
            'contests' => $user->contests()->count(),
            'points' => $user->points,
            'achievements' => $user->achievements()->count(),
            'best_contest' =>$bestContest != null ? new UserContestResource( $bestContest) :null,
            'max_streak' => $user->LongestStreak(),
            'current_streak' => $user->CurrentStreak(),
        ]);
    }
}
