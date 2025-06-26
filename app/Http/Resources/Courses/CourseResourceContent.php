<?php

namespace App\Http\Resources\Courses;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResourceContent extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $userId = auth('api')->id();

        $isEnrolled = $this->students->contains($userId);

        $videos = $this->videos;
        $tests = $this->tests;

        $totalVideos = $videos->count();

        $watchedVideos = $videos->filter(function ($video) use ($userId) {
            $studentProgress = $video->students->firstWhere('id', $userId);
            return $studentProgress?->pivot?->is_completed == true;
        })->count();

        $videoProgressPercentage = $totalVideos > 0
            ? round(($watchedVideos / $totalVideos) * 100)
            : 0;

        $content = $videos->map(function ($video) use ($userId,$isEnrolled) {
            $studentProgress = $video->students->firstWhere('id', $userId);
            return [
                'id' => $video->id,
                'title' => $video->title,
                'type' => 'video',
                'order' => $video->order,
                'progress' => $studentProgress?->pivot?->progress ?? 0 .' %',
                'is_free' => $isEnrolled || $video->free,
                'watched' => $studentProgress?->pivot?->is_completed == true,
            ];
        })->merge(
            $tests->map(function ($test) use ($userId) {
                $studentProgress = $test->students->firstWhere('id', $userId);
                return [
                    'id' => $test->id,
                    'title' => $test->title,
                    'type' => 'test',
                    'order' => $test->order,
                    'is_final' => (bool) $test->is_final,
                    'completed' => $studentProgress !== null,
                ];
            })
        )->sortBy('order')->values();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'is_enrolled' => $isEnrolled,
            'total_videos' => $totalVideos,
            'watched_videos' => $watchedVideos,
            'course_progress' => $videoProgressPercentage.' %',
            'content' => $content,
        ];
        }
}
