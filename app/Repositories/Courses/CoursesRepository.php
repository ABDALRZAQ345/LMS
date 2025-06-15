<?php

namespace App\Repositories\Courses;

use App\Http\Resources\Courses\CourseWithContentResource;
use App\Models\Course;
use App\Models\LearningPath;
use App\Models\User;

class CoursesRepository
{
    public function getAllCourses($validated)
    {
        if ($validated['status'] == 'all') {
            return Course::orderBy($validated['orderBy'], $validated['direction'])
                ->with('teacher')
                ->withCount('videos')
                ->withCount('tests')
                ->where('verified', true)->paginate($validated['items']);
        } else {
            $user = \Auth::user();

            return $user->allCourses()->orderBy($validated['orderBy'], $validated['direction'])
                ->where('status', $validated['status'])
                ->with('teacher')
                ->withCount('videos')
                ->withCount('tests')
                ->paginate($validated['items']);
        }

    }

    public function showCourse($id)
    {
        return Course::with('learningPaths')
            ->with('teacher')
            ->withCount('videos')
            ->withCount('tests')
            ->where('id', $id)->first();
    }

    public function getAllCoursesInLearningPath($id)
    {
        $learningPath = LearningPath::findOrFail($id);

        return $learningPath->courses()->where('verified', true)
            ->withCount('videos')
            ->withCount('tests')
            ->with('teacher')->get();
    }

    public function showCourseInLearningPath($courseId)
    {
        $course = Course::with('teacher')->findOrFail($courseId);
        $content = $course->content();

        return new CourseWithContentResource($course, $content);
    }

    public function getAllCoursesForUser(User $user): \Illuminate\Pagination\LengthAwarePaginator
    {

        return $user->verifiedCourses()
            ->withCount('videos')
            ->withCount('tests')
            ->with('teacher')->paginate(20);
    }
}
