<?php

namespace App\Http\Requests\Auth;

use App\Rules\Auth\GithubRules\ValidGitHubAccount;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateUserRequest extends FormRequest
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
            'name' => ['required'],
            'image' => ['nullable', 'image','max:4096'],
            'bio' => ['nullable'],
            'gitHub_account' => ['nullable', 'string', new ValidGitHubAccount],
            'age' => ['nullable', 'integer', 'between:1,200'],
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
