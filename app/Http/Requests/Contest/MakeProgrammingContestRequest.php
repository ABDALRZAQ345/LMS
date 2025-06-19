<?php

namespace App\Http\Requests\Contest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class MakeProgrammingContestRequest extends FormRequest
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
            'problems' => ['required', 'array', 'min:1'],
            'problems.*.title' => ['required', 'string'],
            'problems.*.description' => ['required', 'string'],
            'problems.*.time_limit' => ['required', 'integer', 'min:1', 'max:5'],
            'problems.*.memory_limit' => ['required', 'integer', 'min:1', 'max:512'],
            'problems.*.input' => ['required', 'string'],
            'problems.*.output' => ['required', 'string'],
            'problems.*.test_input' => ['required', 'string'],
            'problems.*.expected_output' => ['required', 'string'],
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
