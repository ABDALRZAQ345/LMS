<?php

namespace App\Responses;

use App\Http\Resources\AchievementCollection;
use App\Http\Resources\AchievementResource;
use App\Http\Resources\CertificateResource;
use App\Http\Resources\UserContestResource;
use App\Http\Resources\UserResource;
use App\Models\Certificate;
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
