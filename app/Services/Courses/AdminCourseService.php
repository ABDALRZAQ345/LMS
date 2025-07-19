<?php

namespace App\Services\Courses;

use App\Helpers\ResponseHelper;
use App\Http\Resources\Courses\AdminRequestCoursesResource;
use App\Repositories\Courses\AdminCourseRepository;

class AdminCourseService
{
    protected $adminCourseRepo;
    public function __construct(AdminCourseRepository $adminCourseRepo)
    {
        $this->adminCourseRepo = $adminCourseRepo;
    }

    public function requestsCourses($validated){
        $courses = $this->adminCourseRepo->requestsCourses($validated);
        $data = [
            'courses' => AdminRequestCoursesResource::collection($courses),
            'meta' => getMeta($courses),
        ];

        return ResponseHelper::jsonResponse($data,'Get Requests Courses Successfully');
    }

    public function UpdateCourseRequestStatus($course,string $status)
    {
        if ($status == 'accepted') {
            return $course->update([
                'request_status' => $status,
                'verified' => 1,
            ]);
        }else{
            return $course->update([
                'request_status' => $status,
                'verified' => 0,
            ]);
        }
    }
}
