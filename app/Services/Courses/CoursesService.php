<?php

namespace App\Services\Courses;

use App\Helpers\ResponseHelper;
use App\Http\Resources\Courses\CourseResource;
use App\Repositories\Courses\CoursesRepository;

class CoursesService
{
    public $coursesRepository;
    public function __construct(CoursesRepository $coursesRepository)
    {
        $this->coursesRepository = $coursesRepository;
    }

    public function getAllCourses($validated){

        $courses = $this->coursesRepository->getAllCourses($validated);
        $data = [
            'courses' => CourseResource::collection($courses),
            'total_pages' => $courses->lastPage(),
            'current_page' =>$courses->currentPage(),
            'hasMorePages' => $courses->hasMorePages(),
        ];

        return ResponseHelper::jsonResponse($data, 'Get All Courses Successfully');
    }

    public function showCourse($id){
        $course = $this->coursesRepository->showCourse($id);

        return ResponseHelper::jsonResponse($course ,'Get Course Successfully');
    }

    public function getAllCoursesInLearningPath($learningPathTitle,$learningPathId){
        $coursesInLearningPath = $this->coursesRepository->getAllCoursesInLearningPath($learningPathId);
        return ResponseHelper::jsonResponse(CourseResource::collection($coursesInLearningPath),'Get All Courses In '
            .$learningPathTitle.
            ' Successfully');
    }

    public function showCourseInLearningPath($learningPathName,$courseId){
        $course = $this->coursesRepository->showCourseInLearningPath($courseId);

        return ResponseHelper::jsonResponse($course,'Get Course In Learning Path '
            .$learningPathName.' Successfully');
    }


}
