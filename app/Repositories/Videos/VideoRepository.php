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

    public function updateProgress($course, $video, int $progress): bool
    {
        $userId = auth()->id();
        $now    = now();

        $isCompleted = $progress >= 100 ? 1 : 0;

        $exists = \DB::table('user_video_progress')
            ->where('user_id',  $userId)
            ->where('course_id', $course->id)
            ->where('video_id',  $video->id)
            ->exists();

        if ($exists) {
            $updated = \DB::table('user_video_progress')
                ->where('user_id',  $userId)
                ->where('course_id', $course->id)
                ->where('video_id',  $video->id)
                ->update([
                    'progress'         => $progress,
                    'is_completed'     => $isCompleted,
                    'last_watched_at'  => $now,
                    'updated_at'       => $now,
                ]);

            return $updated >= 0;
        }

        return \DB::table('user_video_progress')->insert([
            'user_id'          => $userId,
            'course_id'        => $course->id,
            'video_id'         => $video->id,
            'progress'         => $progress,
            'is_completed'     => $isCompleted,
            'last_watched_at'  => $now,
            'created_at'       => $now,
            'updated_at'       => $now,
        ]);
    }

}
