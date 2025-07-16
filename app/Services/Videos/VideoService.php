<?php

namespace App\Services\Videos;

use App\Helpers\ResponseHelper;
use App\Http\Resources\Videos\ShowVideoResource;
use App\Repositories\Videos\VideoRepository;

class VideoService
{
    protected $videoRepository;
    public function __construct(VideoRepository $videoRepository)
    {
        $this->videoRepository = $videoRepository;
    }

    public function show($course,$video){
        if ($video->course_id !== $course->id) {
            return ResponseHelper::jsonResponse([], 'This video does not belong to the selected course.',
                404, false);
        }

        $userId = auth()->id();
        $isEnroll = $this->isEnroll($userId,$course->id);
        if($video->free || $isEnroll){
            $getVideo = $this->videoRepository->show($course,$video);
            return ResponseHelper::jsonResponse(ShowVideoResource::make( $getVideo ),'Get video successfully');
        }
        else{
            return ResponseHelper::jsonResponse([],'you should enroll the course first',400,false);
        }

    }

    public function updateProgress($course, $video, $validatedData)
    {
        if ($video->course_id !== $course->id) {
            return ResponseHelper::jsonResponse([], 'This video does not belong to the selected course.',
                404, false);
        }
        $isUpdated = $this->videoRepository->updateProgress($course, $video, $validatedData);

        if ($isUpdated) {
            return ResponseHelper::jsonResponse([], 'Progress updated successfully.');
        }

        return ResponseHelper::jsonResponse([], 'Failed to update progress. Please try again.');
    }

    public function finishedVideo($course,$video){
        if ($video->course_id !== $course->id) {
            return ResponseHelper::jsonResponse([], 'This video does not belong to the selected course.',
                404, false);
        }
        \DB::table('user_video_progress')
            ->where('user_id',auth()->id())
            ->where('video_id',$video->id)
            ->update(['is_completed' => 1,
                'progress'=>100]);
        return ResponseHelper::jsonResponse([], 'Video Completed successfully.');
    }

    public function isEnroll($userId,$courseId){
        $query = \DB::table('course_user')
            ->where('user_id',$userId)
            ->where('course_id',$courseId)
            ->where('status',['enrolled','finished'])
            ->exists();

        if($query){
            return true;
        }
        return false;
    }
}
