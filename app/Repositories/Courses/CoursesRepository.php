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

        $query = Course::orderBy($validated['orderBy'], $validated['direction'])
            ->where('verified', true)
            ->with('teacher')
            ->with('learningPaths')
            ->with(['students' => fn($q) => $q->where('user_id', $user->id)])
            ->with(['videos' => function ($q) use ($user) {
                $q->with(['students' => fn($q2) => $q2->where('user_id', $user->id)]);
            }])
            ->with(['tests' => function ($q) use ($user) {
                $q->with(['students' => fn($q2) => $q2->where('user_id', $user->id)]);
            }])
            ->withSum('videos', 'duration');

        if($validated['status'] !== 'all'){
            $query->whereHas('students', function ($q) use ($user, $validated) {
                $q->where('user_id', $user->id)
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
        $user = auth()->user();

        $course = Course::where('verified', true)
            ->with(['students' => fn($q) => $q->where('user_id', $user->id)])
            ->with(['videos.students' => fn($q) => $q->where('user_id', $user->id )])
            ->with(['tests.students' => fn($q) => $q->where('user_id', $user->id )])
            ->with('teacher')
            ->with('learningPaths')
            ->findOrFail($id);
        $course->teacher->loadCount('verifiedCourses');

        return $course;
    }

    public function showCourseContent($cousreId){
        $user = auth()->user();
        $course = Course::where('verified',true)->with([
            'videos.students' => fn($q) => $q->where('user_id', $user->id),
            'tests.students' => fn($q) => $q->where('user_id', $user->id),
            'students' => fn($q) => $q->where('user_id', $user->id),
        ])->findOrFail($cousreId);

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
        $user = auth()->user();
        $learningPath = LearningPath::findOrFail($id);

        return $learningPath->courses()->where('verified', true)
            ->withCount('videos')
            ->withCount('tests')
            ->withSum('videos', 'duration')
            ->with([
                'students' => fn($q) => $q->where('user_id', $user->id),
                'teacher',
                'videos' => fn($q) => $q->with([
                    'students' => fn($q2) => $q2->where('user_id', $user->id)
                ]),
                'tests' => fn($q) => $q->with([
                    'students' => fn($q2) => $q2->where('user_id', $user->id)
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
