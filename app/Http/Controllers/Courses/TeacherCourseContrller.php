<?php

namespace App\Http\Controllers\Courses;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Courses\CreateCourseRequest;
use App\Http\Requests\Courses\UpdateCourseRequest;
use App\Models\Course;
use App\Services\Courses\TeacherCoursesService;
use Illuminate\Http\Request;

class TeacherCourseContrller extends Controller
{
    protected $teacherCoursesService;
    public function __construct(TeacherCoursesService $teacherCoursesService)
    {
        $this->teacherCoursesService = $teacherCoursesService;
    }

    public function index(Request $request){
        $items = $request->input('items', 10);

        return $this->teacherCoursesService->getTeacherCourses($items);
    }

    public function getMyVerifiedCourses(){
        return $this->teacherCoursesService->getTeacherVerifiedCourses();
    }

    public function showCourseDescription(Course $course){
        return $this->teacherCoursesService->showCourseDescription($course);
    }

    public function showCourseContent(Course $course){
        return $this->teacherCoursesService->showCourseContent($course);
    }

    public function create(CreateCourseRequest $request){
        $validated = $request->validated();
        return $this->teacherCoursesService->createCourse($validated);
    }

    public function update(Course $course ,UpdateCourseRequest $request){
        $validated = $request->validated();
        return $this->teacherCoursesService->updateCourse($course,$validated);
    }

    public function delete(Course $course){
        $hasStudents = $course->students()->exists();
        if($hasStudents){
           return ResponseHelper::jsonResponse([],'You cannot delete this course. It has active students',403,false);
        }
        if($course->image){
            $this->teacherCoursesService->deleteOldImage($course->image);
        }
        $course->delete();
        return ResponseHelper::jsonResponse([],'Deleted Course Successfully');
    }

    public function order(Course $course){

    }

}
