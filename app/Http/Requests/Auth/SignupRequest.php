<?php

namespace App\Http\Requests\Auth;

use App\Rules\SignupEmail;
use App\Rules\ValidGitHubAccount;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password;

class SignupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
        return [
            'name' => ['required', 'string'],
            'password' => ['required', 'confirmed', Password::defaults(), 'max:40'],
            'email' => ['required',new SignupEmail() ],
            'image' => ['nullable', 'image', 'max:512'],
            'fcm_token' => ['nullable', 'string'],
            'gitHub_account' => ['nullable', 'string', new ValidGitHubAccount],
            'bio' => ['nullable', 'string'],
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
