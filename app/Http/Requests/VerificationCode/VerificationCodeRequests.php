<?php

namespace App\Http\Requests\VerificationCode;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class VerificationCodeRequests extends FormRequest
{
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $isRegistration = filter_var($this->input('registration'), FILTER_VALIDATE_BOOLEAN);
            $email = $this->input('email');

            $user = User::where('email', $email)->first();

            if ($isRegistration) {
                if (! $user) {
                    $validator->errors()->add('email', 'This email is not registered.');
                } elseif ($user->email_verified) {
                    $validator->errors()->add('email', 'This user is already verified.');
                }
            } else {
                if (! $user || ! $user->email_verified) {
                    $validator->errors()->add('email', 'This email is not registered or not verified.');
                }
            }
        });
    }
}
