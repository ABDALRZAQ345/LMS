<?php

namespace App\Repositories\Videos;

use App\Models\Course;
use App\Models\Video;
use Illuminate\Support\Str;
use Cloudinary\Cloudinary;
use Illuminate\Support\Facades\DB;

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


}
