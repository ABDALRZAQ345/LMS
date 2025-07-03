<?php

namespace App\Http\Requests\Contest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ShowProblemSubmissionsRequest extends FormRequest
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
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'language' => ['nullable','in:cpp,csharp,java,python,all'],
            'status' => ['nullable','in:wrong_answer,accepted,pending,runtime_error,memory_limit_exceeded,time_limit_exceeded,compile_error,all'],
            'items' => ['nullable', 'integer', 'min:10', 'max:30'],

        ];
    }
    public function prepareForValidation(): void
    {
        $this->merge([
            'language' => $this->filled('language') ? $this->input('language') : 'all',
            'status' => $this->filled('status') ? $this->input('status') : 'all',
            'items' => $this->filled('items') ? $this->input('items') : 30,
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
