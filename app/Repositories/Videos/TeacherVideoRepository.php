<?php

namespace App\Repositories\Videos;

use App\Models\Course;
use App\Models\Video;

class TeacherVideoRepository
{

    public function showOneVideoInCourse($course, $video){
        return Video::where('course_id', $course->id)
            ->where('video_id', $video->id)
            ->get();
    }
    public function createUrl($validate){
        $course = Course::findOrFail($validate['course_id']);
        $validate['order']= $course->content()->count() + 1;
        $video = Video::create($validate);
        return $video;
    }
}
