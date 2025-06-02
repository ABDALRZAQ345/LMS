<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\ServerErrorException;
use App\Exceptions\UNAuthorizedException;
use App\Exceptions\VerificationCodeException;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Password\ForgetPasswordRequest;
use App\Http\Requests\Password\ResetPasswordRequest;
use App\Models\User;
use App\Responses\LogedInResponse;
use App\Services\Auth\VerificationCodeService;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends BaseController
{
    protected VerificationCodeService $verificationCodeService;

    public function __construct(VerificationCodeService $verificationCodeService)
    {
        $this->verificationCodeService = $verificationCodeService;
    }

    /**
     * @throws ServerErrorException
     * @throws VerificationCodeException
     * @throws \Throwable
     */
    public function forget(ForgetPasswordRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $this->verificationCodeService->Check($validated['email'], $validated['code'], false);

        $user = User::where('email', $validated['email'])->firstOrFail();
        UserService::updatePassword($user, $validated['password']);

        $this->verificationCodeService->delete($validated['email'], false);

        return LogedInResponse::response($user);

    }

    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function reset(ResetPasswordRequest $request): JsonResponse
    {

        $validated = $request->validated();

        $user = Auth::user();
        return $this->ResetPassword($validated, $user);
    }

    /**
     * @param mixed $data* @return JsonResponse
     * @throws UNAuthorizedException
     */
    public function ResetPassword(mixed $data, User $user): JsonResponse
    {
        if (Hash::check($data['old_password'], $user->password)) {

            UserService::updatePassword($user, $data['new_password']);

            return response()->json([
                'status' => true,
                'message' => 'Password reset successfully!',
            ]);
        }
        throw new UNAuthorizedException('Wrong old password!');
    }
}
