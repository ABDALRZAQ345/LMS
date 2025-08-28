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
            'url'  => ['nullable', 'url', 'required_without:file'],
            'file' => [
                'nullable',
                'file',
                'mimetypes:video/mp4,video/x-msvideo,video/quicktime,video/webm,video/x-matroska',
                'max:51200',
                'required_without:url',
            ],
            'title' => 'required|string|max:100',
            'description' => 'required|string',
            'free' => 'required|boolean',
            'duration' => 'nullable|integer|min:1',
            'course_id' => 'required|integer',
        ];
    }
}
