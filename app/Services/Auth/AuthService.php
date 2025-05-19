<?php

namespace App\Services\Auth;

use App\Exceptions\UNAuthorizedException;
use App\Exceptions\VerificationCodeException;
use App\Jobs\SendVerificationCode;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    protected VerificationCodeService $verificationCodeService;

    public function __construct(verificationCodeService $verificationCodeService)
    {
        $this->verificationCodeService = $verificationCodeService;
    }

    /**
     * @throws AuthenticationException
     * @throws \Throwable
     */
    public function attemptLogin(array $credentials, array $validated): User
    {
        $user = User::where('email', $validated['email'])
            ->where('email_verified', true)
            ->first();

        if ((! JWTAuth::attempt($credentials)) || (! $user)) {
            throw new UNAuthorizedException('Invalid email or password');
        }

        $user->fcm_token = $validated['fcm_token'] ?? null;
        $user->save();

        return $user;
    }

    /**
     * @throws VerificationCodeException
     * @throws \Throwable
     */
    public function attemptRegister($validated): User
    {

        return
            DB::transaction(function () use ($validated) {
                UserService::deleteUnVerifiedUser($validated['email']);
                $this->verificationCodeService->delete($validated['email'], true);
                SendVerificationCode::dispatch($validated['email'], true);

                return UserService::createUser($validated);
            });

    }
}
