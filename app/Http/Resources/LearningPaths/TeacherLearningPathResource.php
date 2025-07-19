<?php

namespace App\Http\Resources\LearningPaths;

use App\Http\Resources\Courses\TeachersAllCoursesResource;
use App\Http\Resources\Users\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherLearningPathResource extends JsonResource
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
            'image' =>  config('app.url').$this->image,
            'request_status' => $this->request_status,
            'teacher' => $this->relationLoaded('teacher')
                ? UserResource::make($this->teacher)
                : 'mine',
            'courses' => $this->relationLoaded('courses')
                ? TeachersAllCoursesResource::collection($this->courses)
                : null,

        ];
    }
}
