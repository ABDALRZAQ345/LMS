<?php

namespace App\Rules;

use App\Models\Course;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCourseAmount implements ValidationRule
{
    protected int $courseId;

    public function __construct(int $courseId)
    {
        $this->courseId = $courseId;
    }

    public function validate(string $attribute, $value, \Closure $fail): void
    {
        $course = Course::find($this->courseId);

        if (! $course || $value !== ($course->price * 100)) {
            $fail('The amount paid does not match the course price.');
        }
    }
}
