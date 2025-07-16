<?php

namespace App\Http\Controllers\Videos;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Video;
use App\Services\Videos\VideoService;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    protected $videoService;
    public function __construct(VideoService $videoService)
    {
        $this->videoService = $videoService;
    }

    public function show(Course $course , Video $video){

        return $this->videoService->show($course, $video);
    }

    public function updateProgress(Course $course , Video $video,Request $request){
        $validate = $request->validate([
            'progress' => ['required','numeric','min:0','max:100'],
        ]);
        return $this->videoService->updateProgress($course,$video,$validate);
    }

    public function finishedVideo(Course $course , Video $video){
        return $this->videoService->finishedVideo($course,$video);
    }
}
