<?php

namespace App\Http\Requests\LearningPath;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AdminGetAllLearningPathsRequest extends FormRequest
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
            'items' => ['nullable', 'integer', 'min:10', 'max:20'],
            'direction' => ['nullable', 'string', 'in:asc,desc'],
            'orderBy' => ['nullable', 'string', 'in:title,date'],
            'status' => ['nullable', 'string', 'in:all,pending,accepted,rejected'],
            'search' => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {

        $this->merge([
            'items' => $this->input('items', 20),
            'direction' => $this->input('direction', 'asc'),
            'status' => $this->input('status', 'all'),
            'search' => $this->input('search', ''),
            'orderBy' => $this->input('orderBy', 'title'),
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
