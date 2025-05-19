<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetProjectsRequest extends FormRequest
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
            'tag' => ['nullable', 'string'],
            'search' => ['nullable', 'string'],
        ];
    }
    public function prepareForValidation(): void
    {
        $this->merge([
            'tag' => $this->filled('tag') ? $this->input('tag') : 'all',
            'search' => $this->filled('search') ? $this->input('search') : '',
        ]);
    }
}
