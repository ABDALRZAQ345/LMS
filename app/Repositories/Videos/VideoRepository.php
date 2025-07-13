<?php

namespace App\Repositories\Videos;

use App\Models\Course;
use App\Models\Video;

class VideoRepository
{
    public function show($course,$video){
        $userId = auth()->id();
        return Video::where('id',$video->id)
            ->where('course_id',$course->id)
            ->with(['students' => function ($q) use ($userId) {
                $q->where('user_id', $userId);
            }])
            ->first();
    }

    public function updateProgress($course,$video,$validate){
        $exists = \DB::table('user_video_progress')
            ->where('user_id',auth()->id())
            ->where('course_id',$course->id)
            ->where('video_id',$video->id)
            ->exists();
        if($exists) {
            $updated = \DB::table('user_video_progress')
                ->where('user_id', auth()->id())
                ->where('course_id', $course->id)
                ->where('video_id', $video->id)
                ->update(['progress' => $validate['progress']]);
            return $updated > 0;
        }
        else{
            $created = \DB::table('user_video_progress')
                ->insert([
                    'user_id' => auth()->id(),
                    'course_id' => $course->id,
                    'video_id' => $video->id,
                    'progress' => $validate['progress'],
                    'last_watched_at' =>now(),
                ]);
            return $created;
        }
    }

}
