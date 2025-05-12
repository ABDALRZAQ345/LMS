<?php

namespace App\Http\Resources;

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
        $data=parent::toArray($request);
        $returned=[
            'id' => $data['id'],
            'name' => $data['name'],
            'data' => $data['start_at'],
            'rank' => $data['pivot']['rank'],
            'points' => $data['pivot']['gained_points'],
            'type' => $data['type']
        ];
        return $returned;
    }
}
