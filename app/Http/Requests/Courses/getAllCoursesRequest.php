<?php

namespace App\Http\Requests\Courses;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class getAllCoursesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => ['nullable', 'integer', 'min:10', 'max:20'],
            'direction' => ['nullable', 'string', 'in:asc,desc'],
            'orderBy' => ['nullable', 'string', 'in:title,rate,date'],
            'status' => ['nullable', 'string', 'in:all,finished,enrolled,watch_later'],
            'search' => ['nullable', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $orderBy = $this->input('orderBy', 'rate');
        if ($orderBy === 'date') {
            $orderBy = 'created_at';
        }

        $this->merge([
            'items' => $this->input('items', 20),
            'direction' => $this->input('direction', 'asc'),
            'status' => $this->input('status', 'all'),
            'search' => $this->input('search', ''),
            'orderBy' => $orderBy,
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
