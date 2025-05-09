<?php

namespace App\Services;

use App\Exceptions\VerificationCodeException;
use App\Mail\SendEmail;
use App\Models\User;
use App\Models\VerificationCode;
use App\Responses\LogedInResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;

class VerificationCodeService
{
    public static function Send($email,$registration): void
    {
        $code = rand(100000, 999999);

        VerificationCode::create([
            'email' => $email,
            'code' => Hash::make($code),
            'expires_at' => now()->addMinutes(30),
            'registration' => $registration
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
    public static function CheckAndHandle($email, $code,$registration): \Illuminate\Http\JsonResponse
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

        $verificationCode->verified_at = now();
        $verificationCode->save();
        return self::handle($registration, $email);

    }

    /**
     * @param $registration
     * @param $email
     * @return \Illuminate\Http\JsonResponse
     */
    public static function handle($registration, $email): \Illuminate\Http\JsonResponse
    {
        if ($registration) {
            $user = User::where('email', $email)->first();
            $data = ['token' => JWTAuth::fromUser($user), 'role' => $user->role];
            return LogedInResponse::response($data);
        } else {
            // todo changing password
            return response()->json(['message' => 'verification code is ok']);
        }
    }

    /**
     * @throws VerificationCodeException
     */
    public function delete($email,$registration): void
    {

        VerificationCode::where('email',$email)
            ->where('registration',$registration)
            ->delete();

    }
}
