<?php

namespace App\Responses;

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class LogedInResponse
{
    public static function response($data): \Illuminate\Http\JsonResponse
    {
        if ($data instanceof User) {
            $token = JWTAuth::fromUser($data);
            $role = $data['role'];
        } else {
            $token = $data['token'];
            $role = $data['role'];
        }

        return response()->json([
            'message' => true,
            'token' => $token,
            'role' => $role,
        ]);
    }
}
