<?php

namespace App\Services\Videos;

use App\Helpers\ResponseHelper;
use App\Http\Resources\Videos\TeacherVideoResource;
use App\Jobs\UpdateVideoDurationJob;
use App\Models\Course;
use App\Models\Video;
use App\Repositories\Videos\TeacherVideoRepository;
use Dotenv\Loader\Resolver;
use Illuminate\Http\UploadedFile;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Services\Videos\BunnyStreamService;
use Illuminate\Support\Str;

class TeacherVideoService
{
    protected $teacherVideoRepository;
    protected $bunnyStreamService;

    public function __construct(TeacherVideoRepository $teacherVideoRepository,BunnyStreamService $bunnyStreamService)
    {
        $this->teacherVideoRepository = $teacherVideoRepository;
        $this->bunnyStreamService = $bunnyStreamService;
    }

    public function getAllVideoInCourse($course){
        $videos = Video::where('course_id', $course->id)
            ->orderBy('order', 'asc')
            ->get();

        return ResponseHelper::jsonResponse($videos , 'Get Videos In Course');
    }

    public function showOneVideoInCourse($course, $video){
       $theVideo = $this-> teacherVideoRepository->showOneVideoInCourse($course, $video);

       return ResponseHelper::jsonResponse(TeacherVideoResource::make( $theVideo ) , 'Get Video In Course');
    }
    public function createUrl($validate){

        $video = $this->teacherVideoRepository->createUrl($validate);

        return ResponseHelper::jsonResponse($video, 'Add video to course successfully.');
    }

    public function updateUrl($video, $validate){
        $video->update($validate);

        return ResponseHelper::jsonResponse($video,'Updated Video Successfully');
    }

    public function deleteVideo($video){
        $this->teacherVideoRepository->deleteVideo($video);

        return  ResponseHelper::jsonResponse([],'Deleted video successfully');
    }

    public function uploadVideo($data)
    {
        /** @var \Illuminate\Http\UploadedFile $file */
        $file = $data['url'];
        $course = Course::findOrFail($data['course_id']);
        $data['order']= $course->content()->count() + 1;

        // 1. رفع الفيديو إلى Bunny Stream
        $bunnyVideoId = $this->bunnyStreamService->uploadVideo($file, $data['title']);

        // 2. إنشاء رابط المشغل (Iframe)
        $libraryId = config('services.bunny.library_id');
        $iframeUrl = "https://iframe.mediadelivery.net/embed/{$libraryId}/{$bunnyVideoId}";

        // 3. حفظ الفيديو في قاعدة البيانات عبر الـ Repository
        $video = Video::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'order' =>$data['order'],
            'bunny_video_id' => $bunnyVideoId,
            'url' => $iframeUrl,
            'free' => $data['free'],
            'duration' => 0,
            'course_id' => $data['course_id'],
        ]);
        UpdateVideoDurationJob::dispatch($bunnyVideoId)->delay(now()->addMinutes(3));
        // 4. إرجاع الرد
        return ResponseHelper::jsonResponse($video, 'Video uploaded successfully');
    }

    public function deleteUploadVideo($video)
    {
        try {
            if ($video->bunny_video_id) {
                $this->bunnyStreamService->deleteVideo($video->bunny_video_id);
            }

            $video->delete();

            return ResponseHelper::jsonResponse([], 'Video deleted successfully');

        } catch (\Exception $e) {
            \Log::error('Failed to delete video: ' . $e->getMessage());

            return ResponseHelper::jsonResponse([], 'Failed to delete video', 500);
        }
    }

    public function storageVideo($data){
        $video = $this->teacherVideoRepository->storageVideo($data);

        return ResponseHelper::jsonResponse(TeacherVideoResource::make($video),'Add video to course successfully.');
    }

    public function updateStorageVideo($video , $validate){
        $updateVideo = $this->teacherVideoRepository->updateStorageVideo($video , $validate);

        return ResponseHelper::jsonResponse(TeacherVideoResource::make( $updateVideo ),'Updated Video Successfully');
    }

}
