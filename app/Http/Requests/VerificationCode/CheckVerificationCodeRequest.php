<?php

namespace App\Http\Requests\VerificationCode;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CheckVerificationCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $isRegistration = filter_var($this->input('registration'), FILTER_VALIDATE_BOOLEAN);
            $email = $this->input('email');

            $user = User::where('email', $email)->first();

            if ($isRegistration) {
                if(!$user){
                    $validator->errors()->add('email', 'There is no user with this email');
                }
                else if ($user && $user->email_verified) {
                    $validator->errors()->add('email', 'This user is already verified.');
                }
            } else {
                if (! $user || ! $user->email_verified) {
                    $validator->errors()->add('email', 'This email is not registered or not verified.');
                }
            }
        });
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isRegistration = filter_var($this->input('registration'), FILTER_VALIDATE_BOOLEAN);

        return [
            'email' => ['required'],
            'code' => ['required', 'numeric', 'digits:6'],
            'registration' => ['required', 'in:1,0,true,false'],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
