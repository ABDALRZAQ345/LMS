<?php

namespace App\Http\Resources\Videos;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowVideoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {  $student = $this->students->first();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'order' => $this->order,
            'url' => $this->url,
            'duration' => $this->duration,
            'created_at' => $this->created_at->format('Y-m-d'),

            'is_completed' => optional($student?->pivot)->is_completed,
            'progress' => optional($student?->pivot)->progress,
            'last_watched_at' => optional($student?->pivot)->last_watched_at,
        ];
    }
}
