<?php

namespace App\Http\Requests\Videos;

use App\Models\Course;
use Illuminate\Foundation\Http\FormRequest;

class UploadVideoRequest extends FormRequest
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
            'url' => 'required|mimes:mp4,avi,mov|max:51200',
            'title' => 'required|string|max:100',
            'description' => 'required|string',
            'free' => 'required|boolean',
            'course_id' => 'required|integer|exists:courses,id',
        ];
    }
}
