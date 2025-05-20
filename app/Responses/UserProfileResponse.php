<?php

namespace App\Responses;

use App\Http\Resources\Users\UserResource;
use App\Models\User;

class UserProfileResponse
{
    public static function response(User $user): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => true,
            'user' => UserResource::make($user),
        ]);
    }
}
