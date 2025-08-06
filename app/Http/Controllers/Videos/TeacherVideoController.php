<?php

namespace App\Http\Controllers\Videos;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Videos\CreateUrlVedioRequest;
use App\Http\Requests\Videos\UpdateUrlVideoRequest;
use App\Http\Requests\Videos\UploadVideoRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Course;
use App\Models\Video;
use App\Services\Videos\TeacherVideoService;

class TeacherVideoController extends Controller
{
    use AuthorizesRequests;
    protected $teacherVideoService ;
    public function __construct(TeacherVideoService $teacherVideoService)
    {
        $this->teacherVideoService = $teacherVideoService;
    }

    public function index(Course $course){
        $this->authorize('editCourse', $course);
        return $this->teacherVideoService->getAllVideoInCourse($course);
    }

    public function show(Course $course, Video $video){
        $this->authorize('editCourse', $course);
        return $this->teacherVideoService->showOneVideoInCourse($course, $video);
    }
    public function createUrl(CreateUrlVedioRequest $request){
        $validate = $request->validated();

        return $this->teacherVideoService->createUrl($validate);
    }

    public function updateUrl(Video $video,UpdateUrlVideoRequest $request){
        $validate = $request->validated();
        return $this->teacherVideoService->updateUrl($video, $validate);
    }

    public function deleteUrl(Video $video){
        $this->authorize('update', $video);
        $video->delete();
        return  ResponseHelper::jsonResponse([],'Deleted video successfully');
    }

    public function uploadVideo(UploadVideoRequest $request){
        $validate = $request->validated();
        return $this->teacherVideoService->uploadVideo($validate);
    }

    public function deleteUploadVideo(Video $video){
        $this->authorize('update', $video);
        return $this->teacherVideoService->deleteUploadVideo($video);
    }


}
