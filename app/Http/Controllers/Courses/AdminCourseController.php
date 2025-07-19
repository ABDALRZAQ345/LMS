<?php

namespace App\Http\Controllers\Courses;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Courses\AdminGetAllCoursesRequest;
use App\Models\Course;
use App\Services\Courses\AdminCourseService;
use Illuminate\Http\Request;

class AdminCourseController extends Controller
{
    protected $adminCourseService;
    public function __construct(AdminCourseService $adminCourseService)
    {
        $this->adminCourseService = $adminCourseService;
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
        return ResponseHelper::jsonResponse([],'Course accepted successfully ');

    }

    public function reject(Course $course){
        if($course->request_status !== 'pending'){
            if($course->students()->exists()){
                return ResponseHelper::jsonResponse([],'You cannot reject this course. It was accepted and has
                 active students',
                    403,false);
            }
        }
        $this->adminCourseService->UpdateCourseRequestStatus($course,'rejected');
        //todo send notification to teacher
        return ResponseHelper::jsonResponse([],'Course rejected successfully ');
    }

}
