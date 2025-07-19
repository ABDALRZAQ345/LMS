<?php

namespace App\Http\Resources\Courses;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeachersAllCoursesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'id' =>$this->id,
            'title' => $this->title,
            'description' => $this->description,
            'image' => config('app.url').$this->image,

            'teacher_id' => optional($this->teacher)->id,
            'teacher_name' => optional($this->teacher)->name,
            'teacher_image' => optional($this->teacher)->image
                ? config('app.url') . optional($this->teacher)->image
                : null,
        ];
    }
}
