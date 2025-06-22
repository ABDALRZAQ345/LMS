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
        $query=Course::orderBy($validated['orderBy'], $validated['direction'])
            ->with('teacher')
            ->with(['students' => fn($q) => $q->where('user_id', $user->id)])
            ->with(['videos.students' => fn($q) => $q->where('user_id', $user->id )])
            ->with(['tests.students' => fn($q) => $q->where('user_id', $user->id )])
            ->where('verified',true);
        if($validated['status'] != 'all'){
            $query->wherePivot('status', $validated['status']);
        }
        if($validated['search']){
            $query->where('title','like','%'.$validated['search'].'%');
        }

     return $query->paginate($validated['items']);
    }


    public function showCourseDescription($id){
        $user = auth()->user();
        $course = $user->verifiedCourses()
            ->with('teacher')
            ->with('learningPaths')
            ->findOrFail($id);

        return $course;
    }

    public function showCourseContent($cousreId){
        $user = auth()->user();
        $course = Course::findOrFail($cousreId);
        $content = $course->content()
            ->with(['students' => fn($q) => $q->where('user_id', $user->id)])
            ->with(['videos.students' => fn($q) => $q->where('user_id',  $user->id)])
            ->with(['tests.students' => fn($q) => $q->where('user_id', $user->id)])
            ->where('verified',true);
        return $content;
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
