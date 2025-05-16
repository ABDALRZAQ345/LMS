<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\ServerErrorException;
use App\Exceptions\VerificationCodeException;
use App\Http\Controllers\BaseController;
use App\Http\Requests\VerificationCode\CheckVerificationCodeRequest;
use App\Http\Requests\VerificationCode\SendVerificationCodeRequest;
use App\Jobs\SendVerificationCode;
use App\Services\Auth\VerificationCodeService;
use Illuminate\Http\JsonResponse;

class VerificationCodeController extends BaseController
{

    protected VerificationCodeService $verificationCodeService;

    public function __construct(VerificationCodeService $verificationCodeService)
    {
        $this->verificationCodeService = $verificationCodeService;
    }

    /**
     * @throws ServerErrorException
     */
    public function send(SendVerificationCodeRequest $request): JsonResponse
    {
        $validated = $request->validated();

        SendVerificationCode::dispatch($validated['email'], $validated['registration']);

        return response()->json([
            'status' => true,
            'message' => 'Verification code send successfully to '.$validated['email'],
        ]);

    }

    /**
     * @throws VerificationCodeException
     */
    public function Check(CheckVerificationCodeRequest $request): JsonResponse
    {
        $validated = $request->validated();

        return $this->verificationCodeService->CheckAndHandle($validated['email'], $validated['code'], $validated['registration'] ?? true);

    }
}
