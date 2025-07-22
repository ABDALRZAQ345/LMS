<?php

namespace App\Repositories\Courses;

use App\Models\Course;
use App\Models\LearningPath;
use App\Models\User;

class CoursesRepository
{

    public function getAllCourses($validated)
    {
        $userId = auth('api')->id();

        $query = Course::orderBy($validated['orderBy'], $validated['direction'])
            ->where('verified', true)
            ->with('teacher')
            ->with('learningPaths')
            ->with(['students' => fn($q) => $q->where('user_id', $userId)])
            ->with(['videos' => function ($q) use ($userId) {
                $q->with(['students' => fn($q2) => $q2->where('user_id', $userId)]);
            }])
            ->with(['tests' => function ($q) use ($userId) {
                $q->with(['students' => fn($q2) => $q2->where('user_id', $userId)]);
            }])
            ->withSum('videos', 'duration');

        if($userId && $validated['status'] !== 'all'){
            $query->whereHas('students', function ($q) use ($userId, $validated) {
                $q->where('user_id', $userId)
                    ->where('course_user.status', $validated['status']);
            });
        }

        if ($validated['search']) {
            $query->where('title', 'like', '%' . $validated['search'] . '%');
        }

        return $query->paginate($validated['items']);
    }


    public function showCourseDescription($id)
    {
        $userId = auth('api')->id();

        $course = Course::where('verified', true)
            ->with(['students' => fn($q) => $q->where('user_id', $userId)])
            ->with(['videos' => function ($q) use ($userId) {
                $q->with(['students' => fn($q2) => $q2->where('user_id', $userId)]);
            }])
            ->with(['tests' => function ($q) use ($userId) {
                $q->with(['students' => fn($q2) => $q2->where('user_id', $userId)]);
            }])
            ->with('teacher')
            ->with('learningPaths')
            ->findOrFail($id);
        $course->teacher->loadCount('verifiedCourses');

        return $course;
    }

    public function showCourseContent($cousreId){
        $userId = auth('api')->id();
        $courseQuery = Course::where('verified',true)->with([
                'videos.students' => fn($q) => $q->where('user_id', $userId),
                'tests.students' => fn($q) => $q->where('user_id', $userId),
                'students' => fn($q) => $q->where('user_id', $userId),
            ]);


        $course = $courseQuery->findOrFail($cousreId);
        return $course;
    }


    public function showCourse($id){
        return Course::with('learningPaths')
            ->with('teacher')
            ->withCount('videos')
            ->withCount('tests')
            ->where('id',$id)->first();
    }

    public function getAllCoursesInLearningPath($id)
    {
        $userId = auth('api')->id();
        $learningPath = LearningPath::findOrFail($id);

        return $learningPath->courses()->where('verified', true)
            ->withCount('videos')
            ->withCount('tests')
            ->withSum('videos', 'duration')
            ->with([
                'students' => fn($q) => $q->where('user_id', $userId),
                'teacher',
                'videos' => fn($q) => $q->with([
                    'students' => fn($q2) => $q2->where('user_id', $userId)
                ]),
                'tests' => fn($q) => $q->with([
                    'students' => fn($q2) => $q2->where('user_id', $userId)
                ]),
            ])
            ->with('learningPaths')
            ->get();
    }

    public function addToWatchLater($userId,$course){

        $exists = $course->students()
            ->wherePivot('user_id', $userId)
            ->wherePivot('status', 'watch_later')
            ->exists();

        if (!$exists) {
            $course->students()->attach($userId, [
                'status' => 'watch_later',
                'paid' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return true;
        }

        return false;
    }

    public function removeFromWatchLater($userId,$course){

        $query = $course->students()
            ->wherePivot('user_id', $userId)
            ->wherePivot('status', 'watch_later');

        if ($query->exists()) {
            $query->detach();
            return true;
        }

        return false;
    }


//    public function showCourseInLearningPath($courseId){
//        $course =  auth()->user()->verifiedCourses()
//            ->with('teacher')
//            ->with('learningPaths')
//            ->findOrFail($courseId);
//        $content = $course->content();
//
//        return new CourseWithContentResource($course, $content);
//    }

    public function getAllCoursesForUser(User $user): \Illuminate\Pagination\LengthAwarePaginator
    {

        return $user->verifiedCourses()
            ->withCount('videos')
            ->withCount('tests')
            ->with('teacher')
            ->withSum('videos', 'duration')
            ->paginate(20);
    }



}
