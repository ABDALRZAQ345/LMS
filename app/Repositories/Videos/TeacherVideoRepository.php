<?php

namespace App\Repositories\Videos;

use App\Models\Course;
use App\Models\Video;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Str;

class TeacherVideoRepository
{

    public function showOneVideoInCourse($course, $video){
        return Video::where('course_id', $course->id)
            ->where('id', $video->id)
            ->get();
    }
    public function createUrl($validate){
        $course = Course::findOrFail($validate['course_id']);
        $validate['order']= $course->content()->count() + 1;
        $video = Video::create($validate);
        return $video;
    }

    public function uploadVideo($validate)
    {
        $course = Course::findOrFail($validate['course_id']);
        $validate['order'] = $course->content()->count() + 1;
        // 1. رفع الفيديو إلى Cloudinary
        $folderName = Str::slug($course->name);
        // رفع الفيديو
        $response = Cloudinary::uploadVideo(
            $validate['url']->getRealPath(),
            ['folder' => $folderName]
        );

        $uploadedUrl = $response->getSecurePath();
        $publicId = $response->getPublicId();
        $duration = $response->getDuration(); // ✅ المدة بالثواني (float)

        // 2. تحديث القيمة داخل validate
        $validate['url'] = $uploadedUrl;
        $validate['cloudinary_public_id'] = $publicId;
        $validate['duration'] = ceil($duration / 60);

        // 4. حفظه في قاعدة البيانات
        $video = Video::create($validate);

        return $video;
    }

    public function updateUploadVideo($video, $validate){
        // إذا تم رفع فيديو جديد، احذف القديم وارفَع الجديد
        if (isset($validate['url'])) {
            // حذف القديم من Cloudinary
            if ($video->cloudinary_public_id) {
                Cloudinary::destroy($video->cloudinary_public_id, ['resource_type' => 'video']);
            }

            // رفع الجديد
            $response = Cloudinary::uploadVideo(
                $validate['url']->getRealPath(),
                ['folder' => Str::slug($video->course->name)]
            );

            $validate['url'] = $response->getSecurePath();
            $validate['cloudinary_public_id'] = $response->getPublicId();
            $validate['duration'] = ceil($response->getDuration());
        }

        $video->update($validate);
        return $video;
    }

    public function deleteUploadVideo($video){
        if ($video->cloudinary_public_id) {
        Cloudinary::destroy($video->cloudinary_public_id, ['resource_type' => 'video']);
    }

        $video->delete();
    }


}
