<?php

namespace App\Http\Resources\Courses;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResourceDescription extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $countOfVideos = $this->videos->count();
        $duration = $this->videos->sum('duration');

        $countOfParticipant = $this->students()->count();
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'rate' => $this->rate,
            'image' => getPhoto($this->image),
            'price' => $this->price,
            'level'=> $this->level,
            'status' => $this->pivot->status
                ?? optional($this->students->firstWhere('id', auth('api')->id()))?->pivot?->status
                    ?? null,

            'student_paid' => $this->pivot->paid
                ?? optional($this->students->firstWhere('id', auth('api')->id()))?->pivot?->paid
                    ?? null,
            'number_of_videos' => $countOfVideos,
            'duration' => $duration,
            'number_of_participants' => $countOfParticipant,
            'teacher_id' => $this->teacher->id,
            'teacher_name' => $this->teacher->name,
            'teacher_image' => getPhoto($this->teacher->image),
            'teacher_bio' => $this->teacher->bio ?? null,
            'number_of_teacher_courses' => $this->teacher->verified_courses_count,
            'learning_paths' => $this->learningPaths->map(function ($path) {
                return [
                    'id' => $path->id,
                    'title' => $path->title,
                    'image' => getPhoto($path->image)
                ];
            }) ];

    }
}
