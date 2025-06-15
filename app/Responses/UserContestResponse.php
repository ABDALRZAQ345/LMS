<?php

namespace App\Responses;

use App\Http\Resources\Users\UserContestResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserContestResponse
{
    public static function response(User $user): JsonResponse
    {

        $contests=$user->contests()->paginate(20);
        return response()->json([
            'status' => true,
            'contests_count' => $user->contests()->count(),
            'total_points' => $user->points,
            'contests' => UserContestResource::collection($contests),
            'meta' => getMeta($contests)
        ]);

    }
}
