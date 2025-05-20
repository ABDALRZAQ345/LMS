<?php

namespace App\Http\Requests\Users;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class GetUsersRequest extends FormRequest
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
            'friends' => ['in:0,1'],
            'role' => ['in:student,teacher'],
            'search' => ['string'],
            'orderBy' => ['string', 'in:points,name'],
            'direction' => ['string', 'in:asc,desc'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'friends' => $this->filled('friends') ? $this->input('friends') : 0,
            'role' => $this->filled('role') ? $this->input('role') : 'student',
            'search' => $this->filled('search') ? $this->input('search') : '',
            'orderBy' => $this->filled('orderBy') ? $this->input('orderBy') : 'points',
            'direction' => $this->filled('direction') ? $this->input('direction') : 'desc',
        ]);
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
