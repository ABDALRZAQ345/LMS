<?php

namespace App\Responses;

use App\Http\Resources\StudentStandingResource;
use App\Http\Resources\Users\UserResource;
use App\Models\User;

class ContestStandingResponse
{
    public static function response($students,$currentUser): \Illuminate\Http\JsonResponse
    {

        return response()->json([
            'status' => true,
            'message' => "results might not be calculated yet  ",
            'your_order' => $currentUser ? $currentUser->pivot->rank : null,
            'students' => StudentStandingResource::collection($students),
            'meta' => getMeta($students)
        ]);
    }
}
