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

        $data = parent::toArray($request);
        $data['achieve_date'] = Carbon::parse($data['created_at'])->format('Y-m-d');
        unset($data['created_at']);

        return $data;
    }
}
