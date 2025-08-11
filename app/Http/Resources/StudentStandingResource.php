<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentStandingResource extends JsonResource
{
    public static $index = 0;
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
            'end_time' => $this->pivot->end_time,
            'correct_answers' => $this->pivot->correct_answers,
            'gained_points' => $this->pivot->gained_points ?? "not calculated yet",
            'rank' => $this->pivot ?  $this->pivot->rank :  ++self::$index ,
            'image' => $this->image
        ];
    }
}
