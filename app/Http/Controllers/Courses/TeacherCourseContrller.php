<?php

namespace App\Http\Controllers\Courses;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Courses\CreateCourseRequest;
use App\Http\Requests\Courses\ReorderCourseContentRequest;
use App\Http\Requests\Courses\TeacherVerifiedCourseRequest;
use App\Http\Requests\Courses\UpdateCourseRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Course;
use App\Models\Test;
use App\Models\Video;
use App\Services\Courses\TeacherCoursesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherCourseContrller extends Controller
{
    use AuthorizesRequests;
    protected $teacherCoursesService;
    public function __construct(TeacherCoursesService $teacherCoursesService)
    {
        $this->teacherCoursesService = $teacherCoursesService;
    }

    public function myCourses(Request $request){
        $items = $request->input('items', 10);

        return $this->teacherCoursesService->getTeacherCourses($items);
    }
    public function getAllVerifiedCourses(TeacherVerifiedCourseRequest $request){
       $validated = $request->validated();

        return $this->teacherCoursesService->getAllVerifiedCourses($validated);
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
        $this->authorize('editCourse', $course);
        $course->delete();
        return ResponseHelper::jsonResponse([],'Deleted Course Successfully');
    }

    public function reorderContent(ReorderCourseContentRequest $request ,Course $course){
        $orderItems = $request->input('order');

        try {
         db::beginTransaction();



            foreach ($orderItems as $index => $item) {
                [$type, $id] = explode('_', $item);

                $newOrder = $index + 1;

                if ($type === 'video') {
                    $video = Video::where('id', $id)->where('course_id', $course->id)->first();
                    if ($video) {
                        $video->order = $newOrder;
                        $video->save();
                    }
                } elseif ($type === 'test') {
                    $test = Test::where('id', $id)->where('course_id', $course->id)->first();
                    if ($test) {
                        $test->order = $newOrder;
                        $test->save();
                    }
                }

            }

            $finalTest=Test::where('course_id',$course->id)->where('is_final',1)->first();

            if($finalTest){
                $finalTest->order=$newOrder;
            }
           db::commit();
            return ResponseHelper::jsonResponse([], 'Course content reordered successfully');
        }
        catch (\Exception $exception){
            db::rollBack();
            return ResponseHelper::jsonResponse(['error' => $exception->getMessage()],$exception->getMessage());
        }

    }


}
