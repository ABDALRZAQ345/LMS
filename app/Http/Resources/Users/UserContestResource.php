<?php

namespace App\Http\Resources\Users;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserContestResource extends JsonResource
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
            'date' => $this->start_at,
            'rank' => $this->pivot->rank,
            'points' => $this->pivot->gained_points,
            'type' => $this->type,
        ];


    }
}
