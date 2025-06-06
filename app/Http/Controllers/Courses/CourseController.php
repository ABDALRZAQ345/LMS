<?php

namespace App\Http\Controllers\Courses;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Courses\getAllCoursesRequest;
use App\Models\Course;
use App\Models\LearningPath;
use App\Services\Courses\CoursesService;

class CourseController extends Controller
{
    public $courseService;

    public function __construct(CoursesService $courseService)
    {
        $this->courseService = $courseService;
    }

    public function getAllCourses(getAllCoursesRequest $request)
    {

        $validated = $request->validated();

        return $this->courseService->getAllCourses($validated);
    }

    public function showCourse(Course $course)
    {
        return $this->courseService->showCourse($course->id);
    }

    public function getAllCoursesInLearningPath(LearningPath $learningPath)
    {
        return $this->courseService->getAllCoursesInLearningPath($learningPath->title, $learningPath->id);
    }

    public function showCourseInLearningPath(LearningPath $learningPath, Course $course)
    {

        if (! $learningPath->courses()->where('courses.id', $course->id)->exists()) {
            return ResponseHelper::jsonResponse([], 'Course not found in this learning path', 404, false);
        }
        if ($course->verified == 0) {
            return ResponseHelper::jsonResponse([], 'Course not verified', 404, false);
        }

        return $this->courseService->showCourseInLearningPath($learningPath->title, $course->id);
    }
}
