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
            ->with('teacher')->paginate(20);
    }

}
