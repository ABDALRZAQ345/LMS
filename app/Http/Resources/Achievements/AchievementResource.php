<?php

namespace App\Http\Resources\Achievements;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AchievementResource extends JsonResource
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
            'name' => $this->name,
            'image' => getPhoto($this->image),
            'description' => $this->description,
            'achieve_date' => optional($this->pivot?->created_at)->format('Y-m-d'),
        ];
    }
}
