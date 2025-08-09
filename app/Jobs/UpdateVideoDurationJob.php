<?php

namespace App\Jobs;

use App\Models\Video;
use App\Services\Videos\BunnyStreamService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateVideoDurationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $videoId;

    public function __construct(string $videoId)
    {
        $this->videoId = $videoId;
    }

    public function handle(BunnyStreamService $bunnyStreamService): void
    {
        $video = Video::where('bunny_video_id', $this->videoId)->first();

        if (!$video) {
            \Log::warning("Video not found for Bunny ID: {$this->videoId}");
            return;
        }

        $info = $bunnyStreamService->getVideoInfo($this->videoId);

        if (isset($info['length'])) {
            $lengthInSeconds = $info['length'];
            $durationInMinutes = (int) round($lengthInSeconds / 60);

            $video->update([
                'duration' => $durationInMinutes,
            ]);

            \Log::info("Updated video duration to {$durationInMinutes} minutes for ID: {$this->videoId}");
        } else {
            \Log::warning("No duration found in Bunny response for video {$this->videoId}");
        }
    }

}
