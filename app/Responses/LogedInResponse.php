<?php

namespace App\Responses;

class LogedInResponse
{
    public static function response($data): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'message' => true,
            'token' => $data['token'],
            'role' => $data['role'],
        ]);
    }
}
