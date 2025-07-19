<?php

namespace App\Http\Resources\Courses;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class TeacherCourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $imageUrl = config('app.url').$this->image;
        return [
            'id'=>$this->id,
            'title'=> $this->title,
            'description' => $this->description,
            'level' => $this->level,
            'request_status' => $this->request_status,
            'image'=> $imageUrl,
            'rate' => $this->rate,
            'price' => $this->price,
        ];
    }
}
