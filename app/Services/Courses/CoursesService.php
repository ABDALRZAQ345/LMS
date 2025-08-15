<?php

namespace App\Services\Courses;

use App\Helpers\ResponseHelper;
use App\Http\Resources\Courses\AdminRequestCoursesResource;
use App\Http\Resources\Courses\CourseResource;
use App\Http\Resources\Courses\CourseResourceContent;
use App\Http\Resources\Courses\CourseResourceDescription;
use App\Http\Resources\Courses\TeacherCourseResource;
use App\Jobs\SendFirebaseNotification;
use App\Models\Course;
use App\Models\User;
use App\Repositories\Courses\CoursesRepository;
use Stripe\Tax\Transaction;

class CoursesService
{
    public $coursesRepository;

    public function __construct(CoursesRepository $coursesRepository)
    {
        $this->coursesRepository = $coursesRepository;
    }

    public function getAllCourses($validated)
    {
        if($validated['orderBy']=='date'){
            $validated['orderBy']='created_at';
        }
        $courses = $this->coursesRepository->getAllCourses($validated);
        //todo data[]=getmeta($courses)
               $data = [
            'courses' => CourseResource::collection($courses),
            'total_pages' => $courses->lastPage(),
            'current_page' => $courses->currentPage(),
            'hasMorePages' => $courses->hasMorePages(),
        ];

        return ResponseHelper::jsonResponse($data, 'Get All Courses Successfully');
    }
    public function showCourseDescription($id){
        $course = $this->coursesRepository->showCourseDescription($id);
        return ResponseHelper::jsonResponse(CourseResourceDescription::make($course) ,'Get Course Description Successfully');
    }
    public function showCourseContent($courseId){
        $content = $this->coursesRepository->showCourseContent($courseId);

        return ResponseHelper::jsonResponse( CourseResourceContent::make($content),'Get Course Content Successfully');
    }

    public function showCourse($id){
        $course = $this->coursesRepository->showCourse($id);

        return ResponseHelper::jsonResponse(CourseResource::make($course) ,'Get Course Successfully');
    }

    public function getAllCoursesInLearningPath($learningPath)
    {
        $coursesInLearningPath = $this->coursesRepository->getAllCoursesInLearningPath($learningPath->id);

        return ResponseHelper::jsonResponse(CourseResource::collection($coursesInLearningPath), 'Get All Courses In ' .$learningPath->title. ' Successfully');
    }

    public function addToWatchLater($course){
        $userId = auth()->id();

        $isEnrolled = $course->students()
            ->where('user_id', $userId)
            ->where('course_id', $course->id)
            ->whereIn('status', ['enrolled', 'finished'])
            ->exists();

        if ($isEnrolled) {
            return ResponseHelper::jsonResponse([], 'You are already enrolled in this course', 400, false);
        }

        $success = $this->coursesRepository->addToWatchLater($userId,$course);
        if(!$success){
            return ResponseHelper::jsonResponse([],'It is already in watch later');
        }
        else{
            return ResponseHelper::jsonResponse([],'Added to watch later Successfully');
        }

    }
    public function removeFromWatchLater($course){
        $userId = auth()->id();
        $success =  $this->coursesRepository->removeFromWatchLater($userId,$course);

        if($success){
            return ResponseHelper::jsonResponse([],'Removed from watch later Successfully');
        }
        else{
            return ResponseHelper::jsonResponse([],'It is not in watch later',404,false);
        }
    }

    public function enroll($course){
        $user = auth()->user();
        if (!$course->verified) {
            return ResponseHelper::jsonResponse([], 'This course is not verified yet.',404,false);
        }

        if (
            $user->studentCourses()
                ->where('course_id', $course->id)
                ->wherePivotIn('status', ['enrolled', 'finished'])
                ->exists()
        ) {
            return ResponseHelper::jsonResponse([], 'You are already enrolled in this course.');
        }

        if ($course->price == 0 || $user->id == $course->user_id || $user->role == 'admin') {
            $user->studentCourses()->syncWithoutDetaching([
                $course->id => [
                    'paid' => 0,
                    'status' => 'enrolled',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);

            $title = 'Enroll Course '.$course->title;
            $body ="Have a nice trip.";

            SendFirebaseNotification::dispatch($user, $title, $body);
            return ResponseHelper::jsonResponse([], 'You have been enrolled for free.');
        }

        if($user->balance >= $course->price){
            $user->studentCourses()->syncWithoutDetaching([
                $course->id => [
                    'paid' => $course->price,
                    'status' => 'enrolled',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
            $this->updateBalance($user,$course);
            $title = 'Enroll Course '.$course->title;
            $body ="Have a nice trip.";

            SendFirebaseNotification::dispatch($user, $title, $body);
            return ResponseHelper::jsonResponse([], 'You have been enrolled in course.');

        }else{
            return ResponseHelper::jsonResponse([],'You Don\'t have enough balance to enroll this course.'
            ,404,false);
        }

    }

    private function updateBalance($user, $course){
        $teacher = User::findOrFail($course->user_id);
        $admin = User::where('role','admin')->first();
        $user->decrement('balance', $course->price);
        $teacher->increment('balance', $course->price * 0.6);
        $admin->increment('balance', $course->price * 0.4);
    }

}
