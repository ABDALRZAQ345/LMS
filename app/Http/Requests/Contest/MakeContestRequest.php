<?php

namespace App\Http\Requests\Contest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class MakeContestRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'time' => ['required', 'integer', 'min:10', 'max:1440'],
            'description' => ['nullable', 'string'],
            'level' => ['required', 'in:beginner,intermediate,advanced,expert'],
            'start_at' => ['required', 'date', 'date_format:Y-m-d H:i', 'after:now'],
            'questions' => ['required', 'array', 'min:1'],
            'questions.*.question' => ['required', 'string'],
            'questions.*.options' => ['required', 'array', 'min:2'],
            'questions.*.options.*' => ['required', 'array'],
            'questions.*.options.*.is_true' => ['required', 'in:0,1'],
            'questions.*.options.*.option' => ['required', 'string'],
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
