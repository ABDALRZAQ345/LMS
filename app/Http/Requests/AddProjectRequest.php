<?php

namespace App\Http\Requests;

use App\Rules\ValidGitHubAccount;
use Illuminate\Foundation\Http\FormRequest;

class AddProjectRequest extends FormRequest
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
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'technologies' => ['nullable', 'array'],
            // todo rule for github repo
            'gitHub_url' => ['nullable', 'string'],
            'tag_id' => ['required', 'exists:tags,id'],
        ];
    }
}
