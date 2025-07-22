<?php

namespace App\Services\Videos;

use App\Helpers\ResponseHelper;
use App\Models\Video;
use App\Repositories\Videos\TeacherVideoRepository;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class TeacherVideoService
{
    protected $teacherVideoRepository;

    public function __construct(TeacherVideoRepository $teacherVideoRepository)
    {
        $this->teacherVideoRepository = $teacherVideoRepository;
    }

    public function getAllVideoInCourse($course){
        $videos = Video::where('course_id', $course->id)
            ->orderBy('order', 'asc')
            ->get();

        return ResponseHelper::jsonResponse($videos , 'Get Videos In Course');
    }

    public function showOneVideoInCourse($course, $video){
       $theVideo = $this-> teacherVideoRepository->showOneVideoInCourse($course, $video);

       return ResponseHelper::jsonResponse($theVideo , 'Get Video In Course');
    }
    public function createUrl($validate){

        $video = $this->teacherVideoRepository->createUrl($validate);

        return ResponseHelper::jsonResponse($video, 'Add video to course successfully.');
    }

    public function updateUrl($video, $validate){
        $video->update($validate);

        return ResponseHelper::jsonResponse($video,'Updated Video Successfully');
    }

    public function uploadVideo($validate){
        $video = $this->teacherVideoRepository->uploadVideo($validate);

        return ResponseHelper::jsonResponse($video,'Upload video to course successfully.');
    }

    public function updateUploadVideo($video , $validate){
        $updated = $this->teacherVideoRepository->updateUploadVideo($video, $validate);
        return ResponseHelper::jsonResponse($updated, 'Video updated successfully');
    }

    public function deleteUploadVideo($video){
        $this->teacherVideoRepository->deleteUploadVideo($video);

        return ResponseHelper::jsonResponse([], 'Video deleted successfully');
    }
}
