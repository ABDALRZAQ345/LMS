<?php

namespace App\Http\Requests\LearningPath;

use App\Models\LearningPath;
use Illuminate\Foundation\Http\FormRequest;

class CreateLearningPathRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return \Gate::allows('create', LearningPath::class);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image' => ['nullable', 'image'],
            'courses' => ['required', 'array', 'min:1'],
            'courses.*' => ['required', 'exists:courses,id', 'distinct'],
        ];
    }
}
