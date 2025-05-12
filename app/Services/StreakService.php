<?php

namespace App\Services;

use App\Exceptions\UNAuthorizedException;
use App\Exceptions\VerificationCodeException;
use App\Jobs\SendVerificationCode;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class StreakService
{
    public static function CreateStreakLogs(User $user)
    {

    }
}
