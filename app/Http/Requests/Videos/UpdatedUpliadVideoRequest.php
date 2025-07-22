<?php

namespace App\Http\Requests\Videos;

use App\Models\Course;
use Illuminate\Foundation\Http\FormRequest;

class UpdatedUpliadVideoRequest extends FormRequest
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
            'url' => 'nullable|mimes:mp4,avi,mov|max:51200',
            'title' => 'sometimes|string|max:100',
            'description' => 'sometimes|string',
            'free' => 'sometimes|boolean',
            ];
    }
}
