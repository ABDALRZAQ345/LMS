<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\ServerErrorException;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\SignupRequest;
use App\Responses\LogedInResponse;
use App\Responses\LogedOutResponse;
use App\Services\AuthService;
use App\Services\VerificationCodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{
    protected VerificationCodeService $verificationCodeService;

    protected AuthService $authService;

    public function __construct(VerificationCodeService $verificationCodeService, AuthService $authService)
    {
        $this->verificationCodeService = $verificationCodeService;
        $this->authService = $authService;
    }

    /**
     * @throws ServerErrorException
     * @throws \App\Exceptions\VerificationCodeException
     * @throws \Throwable
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

        $data = $this->authService->attemptLogin($credentials, $request->validated());

        return LogedInResponse::response($data);

    }

    /**
     * @throws ServerErrorException
     */
    public function logout(): JsonResponse
    {
        try {
            auth()->logout();

            return LogedOutResponse::response();

        } catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }
    }

    public function refresh(): JsonResponse
    {
        $user = Auth::user();
        $token = auth()->refresh();

        return LogedInResponse::response(['token' => $token, 'role' => $user->role]);

    }

    public function profile(): JsonResponse
    {
        $user = Auth::user();

        return response()->json([
            'status' => true,
            'user' => $user,
        ]);
    }
}
