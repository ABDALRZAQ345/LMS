<?php

namespace App\Responses;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

class LogedInResponse
{
    public static function response($data): JsonResponse
    {
        if ($data instanceof User) {
            $userId = $data['id'];
            $token = JWTAuth::fromUser($data);
            $role = $data['role'];
        } else {
            $userId = $data['user_id'];
            $token = $data['token'];
            $role = $data['role'];
        }

        return response()->json([
            'message' => true,
            'user_id' => $userId,
            'token' => $token,
            'role' => $role,
        ]);
    }
}
