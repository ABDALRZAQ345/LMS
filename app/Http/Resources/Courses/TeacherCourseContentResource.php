<?php

namespace App\Http\Resources\Courses;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherCourseContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $videos = $this->videos;
        $tests = $this->tests;
        $content = $videos->map(function ($video)  {
            return [
                'id' => $video->id,
                'title' => $video->title,
                'type' => 'video',
                'order' => $video->order,
            ];
        })->merge(
            $tests->map(function ($test)  {
                return [
                    'id' => $test->id,
                    'title' => $test->title,
                    'type' => 'test',
                    'order' => $test->order,
                    'is_final' => (bool) $test->is_final,
                ];
            })
        )->sortBy('order')->values();

        return  $content->toArray();
    }
}
