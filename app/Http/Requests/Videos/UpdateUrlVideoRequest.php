<?php

namespace App\Http\Requests\Videos;

use App\Models\Course;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUrlVideoRequest extends FormRequest
{
    public function authorize(): bool
    {
        $courseId = $this->input('course_id');
        $course = Course::find($courseId);

        return \Gate::allows('editCourse', $course);
    }

    public function rules(): array
    {
        return [
            'url' => 'sometimes|url',
            'title' => 'sometimes|string|max:100',
            'description' => 'sometimes|string',
            'free' => 'sometimes|boolean',
            'duration' => 'sometimes|integer|min:1',
            'course_id' => 'sometimes|integer',
        ];
    }
}
