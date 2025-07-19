<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherCreatedLearningPathResource extends JsonResource
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
            'teacher_id' => $this->teacher->id,
            'total_courses_price' => $this->courses_sum_price,
            'teacher_name' => $this->teacher->name,
            'verified' => $this->verified,
            'price' => $this->courses_sum_price,
        ];
    }
}
