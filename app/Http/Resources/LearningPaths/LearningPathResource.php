<?php

namespace App\Http\Resources\LearningPaths;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LearningPathResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'image' => getPhoto($this->image),
            'rate' => $this->courses_sum_rate / $this->courses_count,
            'courses_count' => $this->courses_count,
            'total_courses_price' => $this->courses_sum_price,
            'total_duration' => $this->when(
                $this->relationLoaded('courses'),
                fn () => $this->formatDuration($this->courses->flatMap->videos->sum('duration'))
            ),
            'teacher_id' => $this->teacher->id,
            'teacher_name' => $this->teacher->name,
            'teacher_image'=>$this->teacher->image,
            'teacher_bio' => $this->teacher->bio,
            'teacher_courses_count' => $this->when(
                $this->relationLoaded('teacher') && $this->teacher?->relationLoaded('verifiedCourses'),
                fn () => $this->teacher->verifiedCourses->count()
            ),
            'status'=> $this->pivot->status
                ?? optional($this->students->firstWhere('id', auth('api')->id()))?->pivot?->status
                    ?? null,
        ];
    }
    private function formatDuration($minutes)
    {
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        return "{$hours}h {$remainingMinutes}min";
    }
}
