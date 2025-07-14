<?php

namespace App\Http\Requests\Videos;

use App\Models\Course;
use Illuminate\Foundation\Http\FormRequest;

class CreateUrlVedioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $courseId = $this->input('course_id');
        $course = Course::find($courseId);

        return \Gate::allows('editCourse', $course);
    }

    public function rules(): array
    {
        return [
            'url' => 'required|url',
            'title' => 'required|string|max:100',
            'description' => 'required|string',
            'free' => 'required|boolean',
            'duration' => 'required|integer|min:1',
            'course_id' => 'required|integer',
        ];
    }
}
