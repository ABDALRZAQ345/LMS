<?php

namespace App\Http\Controllers\Courses;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Courses\AdminGetAllCoursesRequest;
use App\Models\Course;
use App\Models\User;
use App\Services\Courses\AdminCourseService;
use App\Services\FirebaseNotificationService;
use Illuminate\Http\Request;

class AdminCourseController extends Controller
{
    protected $adminCourseService;
    protected $firebaseNotificationService;
    public function __construct(AdminCourseService $adminCourseService,FirebaseNotificationService $firebaseNotificationService)
    {
        $this->adminCourseService = $adminCourseService;
        $this->firebaseNotificationService = $firebaseNotificationService;
    }

    public function index(AdminGetAllCoursesRequest $request){
        $validated = $request->validated();
        if($validated['orderBy'] == 'date') {
            $validated['orderBy'] = 'created_at';
        }
        return $this->adminCourseService->requestsCourses($validated);

    }

    public function accept(Course $course){
        $this->adminCourseService->UpdateCourseRequestStatus($course,'accepted');
        //todo send notification to teacher
        $this->adminCourseService->UpdateCourseRequestStatus($course,'rejected');
        $teacher = User::findOrFail($course->user_id);

        $title = 'Your Course has been accepted';
        $body ="Congratulations! Your Course titled \"{$course->title}\" has been accepted.";

        $this->firebaseNotificationService->sendAndStore($teacher, $title, $body);
        return ResponseHelper::jsonResponse([],'Course accepted successfully ');

    }

    public function reject(Course $course , Request $request){
        $request->validate([
           'reason' => 'required|string|max:100'
        ]);
        if($course->request_status !== 'pending'){
            if($course->students()->exists()){
                return ResponseHelper::jsonResponse([],'You cannot reject this course. It was accepted and has
                 active students',
                    403,false);
            }
        }
        $this->adminCourseService->UpdateCourseRequestStatus($course,'rejected');
        //todo send notification to teacher
        $teacher = User::findOrFail($course->user_id);

        $title = 'Your Course has been rejected';
        $body = "Unfortunately, your Course titled \"{$course->title}\" has been rejected. Reason: " . $request['reason'];

        $this->firebaseNotificationService->sendAndStore($teacher, $title, $body);
        return ResponseHelper::jsonResponse([],'Course rejected successfully ');
    }

}
