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
            'title'=>$this->title,
            'description'=>$this->description,
            'image'=>$imageUrl,
            'teacher_name'=>$this->teacher->name,
            'verified'=>$this->verified,
        ];
    }
}
