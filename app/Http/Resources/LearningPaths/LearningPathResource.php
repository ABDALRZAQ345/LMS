<?php

namespace App\Http\Resources\LearningPaths;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class LearningPathResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $imageUrl = $this->image
            ? (str_starts_with($this->image, 'https://via.placeholder.com')
                ? $this->image
                : config('app.url').'/storage/'.$this->image)
            : null;

        return[
            'id'=>$this->id,
            'title'=>$this->title,
            'description'=>$this->description,
            'image'=>$imageUrl,
            'rate'=>$this->courses_sum_rate /$this->courses_count,
            'courses_count'=>$this->courses_count,
            'teacher_id'=>$this->teacher->id,
            'total_courses_price' => $this->courses_sum_price,
            'teacher_name'=>$this->teacher->name,
            'verified'=>$this->verified,
        ];
    }
}
