<?php

namespace App\Services\Auth;

use App\Exceptions\VerificationCodeException;
use App\Mail\SendEmail;
use App\Models\User;
use App\Models\VerificationCode;
use App\Responses\LogedInResponse;
use App\Services\User\StreakService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class VerificationCodeService
{
    protected static StreakService $streakService;

    public function __construct(StreakService $streakService = new StreakService)
    {
        self::$streakService = $streakService;
    }

    public static function Send($email, $registration): void
    {
        $code = rand(100000, 999999);

        VerificationCode::create([
            'email' => $email,
            'code' => Hash::make($code),
            'expires_at' => now()->addMinutes(200),
            'registration' => $registration,
        ]);
        Log::channel('verification_code')->info('the code for '.$email.' is '.$code);
        $message = " {$code} ";
        $subject = 'Verification Code';
        Mail::to($email)->send(new SendEmail($message, $subject));
    }

    /**
     * @throws VerificationCodeException
     */
    public static function Check($email, $code, $registration): true
    {
        $verificationCode = VerificationCode::where('email', $email)
            ->where('registration', $registration)
            ->first();

        if (! $verificationCode || ! Hash::check($code, $verificationCode->code)) {
            throw new VerificationCodeException('incorrect code');
        }
        if ($verificationCode->isExpired()) {
            throw new VerificationCodeException('Expired code');
        }

        return true;
    }

    /**
     * @throws VerificationCodeException
     */
    public static function CheckAndHandle($email, $code, $registration): JsonResponse
    {
        $verificationCode = VerificationCode::where('email', $email)
            ->where('registration', $registration)
            ->first();

        if (! $verificationCode || ! Hash::check($code, $verificationCode->code)) {
            throw new VerificationCodeException('incorrect code');
        }
        if ($verificationCode->isExpired()) {
            throw new VerificationCodeException('Expired code');
        }

        return self::handle($registration, $email, $verificationCode);

    }

    public static function handle($registration, $email, $verificationCode): JsonResponse
    {
        if ($registration) {

            $user = User::where('email', $email)
                ->where('email_verified', false)
                ->first();

            $user->email_verified = true;
            $user->save();
            $verificationCode->delete();
            self::$streakService->CreateStreakLogs($user);

            return LogedInResponse::response($user);
        } else {
            return response()->json(['message' => 'verification code is true ']);
        }
    }

    /**
     * @throws VerificationCodeException
     */
    public function delete($email, $registration): void
    {

        VerificationCode::where('email', $email)
            ->delete();

    }
}
