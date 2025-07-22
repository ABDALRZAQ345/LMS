<?php

namespace App\Http\Resources\Courses;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherCourseDescriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $participants = $this->students()->count();
        $countOfVideos = $this->videos->count();
        $duration = $this->videos->sum('duration');
        return [
            'id'=>$this->id,
            'title'=> $this->title,
            'description' => $this->description,
            'level' => $this->level,
            'request_status' => $this->request_status,
            'image'=> getPhoto($this->image),
            'rate' => $this->rate,
            'price' => $this->price,
            'participants' => $participants,
            'number_of_videos' => $countOfVideos,
            'duration' => $duration,
            'learning_paths' => $this->learningPaths->map(function ($path) {
                return [
                    'id' => $path->id,
                    'title' => $path->title,
                    'image' => $path->image
                        ? (str_starts_with($path->image, 'https://via.placeholder.com')
                            ? $path->image
                            : config('app.url') . '/storage/' . $path->image)
                        : null,
                ];
            })
        ];
    }
}
