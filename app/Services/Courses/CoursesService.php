<?php

namespace App\Services\Courses;

use App\Helpers\ResponseHelper;
use App\Http\Resources\Courses\CourseResource;
use App\Http\Resources\Courses\CourseResourceContent;
use App\Http\Resources\Courses\CourseResourceDescription;
use App\Repositories\Courses\CoursesRepository;

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

//    public function showCourseInLearningPath($learningPathName, $courseId)
//    {
//        $course = $this->coursesRepository->showCourseInLearningPath($courseId);
//
//        return ResponseHelper::jsonResponse($course, 'Get Course In Learning Path '
//            .$learningPathName.' Successfully');
//    }


}
