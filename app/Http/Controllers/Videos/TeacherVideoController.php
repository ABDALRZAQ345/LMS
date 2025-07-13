<?php

namespace App\Http\Controllers\Videos;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Courses\UpdateCourseRequest;
use App\Http\Requests\Videos\CreateUrlVedioRequest;
use App\Models\Course;
use App\Models\Video;
use App\Services\Videos\TeacherVideoService;
use Illuminate\Http\Request;

class TeacherVideoController extends Controller
{
    protected $teacherVideoService ;
    public function __construct(TeacherVideoService $teacherVideoService)
    {
        $this->teacherVideoService = $teacherVideoService;
    }

    public function index(Course $course){
        return $this->teacherVideoService->getAllVideoInCourse($course);
    }

    public function show(Course $course, Video $video){
        return $this->teacherVideoService->showOneVideoInCourse($course, $video);
    }
    public function createUrl(CreateUrlVedioRequest $request){
        $validate = $request->validated();

        return $this->teacherVideoService->createUrl($validate);
    }

    public function updateUrl(Video $video,UpdateCourseRequest $request){
        $validate = $request->validated();
        return $this->teacherVideoService->updateUrl($video, $validate);
    }

    public function deleteUrl(Video $video){
        if(!auth()->id() === $video->user_id){
            return ResponseHelper::jsonResponse([],'You Can\'t Delete a Video that is\'nt yours ',403 , false);
        }
        $video->delete();
        return  ResponseHelper::jsonResponse([],'Deleted video successfully');
    }

}
