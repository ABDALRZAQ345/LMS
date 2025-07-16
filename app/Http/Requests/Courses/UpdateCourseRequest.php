<?php

namespace App\Http\Requests\Courses;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return \Gate::allows('editCourse',$this->route('course'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string', 'max:255'],
            'image' => ['sometimes', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'level' => ['sometimes', 'string', 'in:beginner,intermediate,advanced,expert'],
        ];
    }
}
