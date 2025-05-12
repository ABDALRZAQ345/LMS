<?php

namespace App\Http\Requests\VerificationCode;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CheckVerificationCodeRequest extends VerificationCodeRequests
{
    public function authorize(): bool
    {
        return true;
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
