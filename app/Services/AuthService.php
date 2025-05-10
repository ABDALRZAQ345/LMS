<?php

namespace App\Services;

use App\Exceptions\UNAuthorizedException;
use App\Exceptions\VerificationCodeException;
use App\Jobs\SendVerificationCode;
use App\Models\User;
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
    public function attemptLogin(array $credentials, array $validated): array
    {
        if (! $token = JWTAuth::attempt($credentials)) {
            throw new UNAuthorizedException('Invalid email or password');
        }
        $user = User::where('email', $validated['email'])->first();
        $user->fcm_token = $validated['fcm_token'] ?? null;
        $user->save();

        return [
            'token' => $token,
            'role' => $user->role,
        ];
    }

    /**
     * @throws VerificationCodeException
     * @throws \Throwable
     */
    public function attemptRegister($validated): User
    {

        return
            DB::transaction(function () use ($validated) {
                $this->verificationCodeService->delete($validated['email'], true);
                SendVerificationCode::dispatch($validated['email'], true);

                return UserService::createUser($validated);
            });

    }
}
