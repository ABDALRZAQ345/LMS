<?php

namespace App\Responses;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

class LogedInResponse
{
    public static function response(User $user, $token = null): JsonResponse
    {

        if ($token == null) {
            $token = JWTAuth::fromUser($user);
        }

        return response()->json([
            'status' => true,
            'user_id' => $user['id'],
            'token' => $token,
            'role' => $user['role'],
        ]);
    }
}
