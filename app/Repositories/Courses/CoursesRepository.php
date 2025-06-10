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
        $user = auth()->user();
        if(!$validated['search']){
            if ($validated['status'] === 'all') {
            return Course::orderBy($validated['orderBy'], $validated['direction'])
                ->with('teacher')
                ->with(['students' => fn($q) => $q->where('user_id', $user->id)])
                ->with(['videos.students' => fn($q) => $q->where('user_id', auth()->id())])
                ->with(['tests.students' => fn($q) => $q->where('user_id', auth()->id())])
                ->where('verified',true)
                ->paginate($validated['items']);
        }
            else {
                return $user->verifiedCourses()
                    ->wherePivot('status', $validated['status'])
                    ->orderBy($validated['orderBy'], $validated['direction'])
                    ->with('teacher')
                    ->with(['videos.students' => fn($q) => $q->where('user_id', auth()->id())])
                    ->with(['tests.students' => fn($q) => $q->where('user_id', auth()->id())])
                    ->paginate($validated['items']);
            }
        } else{
            if ($validated['status'] === 'all') {
                return Course::where('title','like','%'.$validated['search'].'%')
                    ->orderBy($validated['orderBy'], $validated['direction'])
                    ->with('teacher')
                    ->with(['students' => fn($q) => $q->where('user_id', $user->id)])
                    ->with(['videos.students' => fn($q) => $q->where('user_id', auth()->id())])
                    ->with(['tests.students' => fn($q) => $q->where('user_id', auth()->id())])
                    ->where('verified',true)
                    ->paginate($validated['items']);
            }
            else {
                return $user->verifiedCourses()
                    ->where('title','like','%'.$validated['search'].'%')
                    ->wherePivot('status', $validated['status'])
                    ->orderBy($validated['orderBy'], $validated['direction'])
                    ->with('teacher')
                    ->with(['videos.students' => fn($q) => $q->where('user_id', auth()->id())])
                    ->with(['tests.students' => fn($q) => $q->where('user_id', auth()->id())])
                    ->paginate($validated['items']);
            }

        }
    }


    public function showCourse($id){
        $course = auth()->user()->verifiedCourses()
            ->with('teacher')
            ->with('learningPaths')
            ->findOrFail($id);
        $content = $course->content();

        return new CourseWithContentResource($course, $content);
    }

    public function getAllCoursesInLearningPath($id) {
        $learningPath = LearningPath::findOrFail($id);
        return $learningPath->courses()->where('verified',true)
            ->withCount('videos')
            ->withCount('tests')
            ->with(['teacher', 'students' => fn($q) => $q->where('user_id', auth()->id())])
            ->with('teacher')
            ->get();
    }


    public function showCourseInLearningPath($courseId){
        $course =  auth()->user()->verifiedCourses()
            ->with('teacher')
            ->with('learningPaths')
            ->findOrFail($courseId);
        $content = $course->content();

        return new CourseWithContentResource($course, $content);
    }
    public function getAllCoursesForUser(User $user): \Illuminate\Database\Eloquent\Collection
    {

        return $user->verifiedCourses()
            ->withCount('videos')
            ->withCount('tests')
            ->with('teacher')
            ->get();
    }

}
