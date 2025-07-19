<?php

namespace App\Http\Requests\LearningPath;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLearningPathRequest extends FormRequest
{

    public function authorize(): bool
    {
        return \Gate::allows('update',$this->route('learningPath'));
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'image' => ['nullable', 'image'],
            'courses' => ['sometimes', 'array', 'min:1'],
            'courses.*' => ['sometimes', 'exists:courses,id', 'distinct'],
        ];
    }
}
