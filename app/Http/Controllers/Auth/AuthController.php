<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\ServerErrorException;
use App\Exceptions\VerificationCodeException;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\SignupRequest;
use App\Responses\LogedInResponse;
use App\Responses\LogedOutResponse;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends BaseController
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @throws \Throwable
     * @throws VerificationCodeException
     */
    public function register(SignupRequest $request): JsonResponse
    {

        $validated = $request->validated();

        $this->authService->attemptRegister($validated);

        return response()->json([
            'status' => true,
            'message' => 'verification code sent successfully',
        ]);

    }

    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function login(LoginRequest $request): JsonResponse
    {

        $credentials = $request->only('email', 'password');

        $user = $this->authService->attemptLogin($credentials, $request->validated());

        return LogedInResponse::response($user);
    }

    /**
     * @throws ServerErrorException
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return LogedOutResponse::response();

    }

    public function refresh(): JsonResponse
    {
        $token = auth()->refresh();
        $user = auth()->user();

        return LogedInResponse::response($user, $token);

    }
}
