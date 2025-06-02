<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\ServerErrorException;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\FcmTokenRequest;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class FcmTokenController extends BaseController
{
    protected UserService  $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * @throws ServerErrorException
     */
    public function send(FcmTokenRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $this->userService->UpdateFcmToken($validated['fcm_token']);

        return response()->json([
            'status' => true,
            'message' => 'token send successfully',
        ]);

    }
}
