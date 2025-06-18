<?php

namespace App\Http\Resources\Courses;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        $countOfVideos = $this->videos->count();

        $countOfCompletedVideos = $this->videos->filter(function ($video) {
            return $video->students->first()?->completed === 1;
        })->count();

        $videoProgressPercentage = $countOfVideos > 0
            ? round(($countOfCompletedVideos / $countOfVideos) * 100)
            : 0;

        $finalTest = $this->tests->firstWhere('is_final', true);
        $finalTestPassed = $finalTest
            ? $finalTest->students->isNotEmpty()
            : false;

        $imageUrl = $this->image
            ? (str_starts_with($this->image, 'https://via.placeholder.com')
                ? $this->image
                : config('app.url').'/storage/'.$this->image)
            : null;

        $teacherImageUrl = $this->teacher->image
            ? (str_starts_with($this->teacher->image, 'https://via.placeholder.com')
                ? $this->teacher->image
                : config('app.url').'/storage/'.$this->teacher->image)
            : null;

        $data = [
            'id' => $this->id,
            'title_of_course' => $this->title,
            'description_of_course' => $this->description,
            'rate' => $this->rate,
            'image_of_course' => $imageUrl,
            'number_of_video' => $countOfVideos,
            'video_progress' => $videoProgressPercentage,
            'final_test_passed' => $finalTestPassed,
            'level'=> $this->level,
            'price' => $this->price,
            'teacher_id' => $this->teacher->id,
            'teacher_name' => $this->teacher->name,
            'teacher_image' => $teacherImageUrl,
            'status' => $this->pivot->status
                ?? optional($this->students->firstWhere('id', auth()->id()))?->pivot?->status
                    ?? null,

            'student_paid' => $this->pivot->paid
                ?? optional($this->students->firstWhere('id', auth()->id()))?->pivot?->paid
                    ?? null,




        ];
        if ($request->is('api/courses/*')) {
            $data['learning_paths'] = $this->whenLoaded('learningPaths', function () {
                return $this->learningPaths->map(function ($path) {
                    return [
                        'id' => $path->id,
                        'name' => $path->title,
                        'image' => $path->image
                            ? (str_starts_with($path->image, 'https://via.placeholder.com')
                                ? $path->image
                                : config('app.url').'/storage/'.$path->image)
                            : null,
                    ];
                });
            });
        }

        return $data;
    }
}
