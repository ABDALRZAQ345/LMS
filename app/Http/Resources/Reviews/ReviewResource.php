<?php

namespace App\Http\Resources\Reviews;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $authId = auth('api')->id();

        return [
            'id' => $this->id,
            'comment' => $this->comment,
            'rate' => $this->rate,
            'created_at' => ($this->updated_at->gt($this->created_at)
                ? $this->updated_at
                : $this->created_at)->toDateTimeString(),
            'your_review' => $this->student->id === $authId,
            'student' => [
                'id' => $this->student->id,
                'name' => $this->student->name,
                'image' => $this->student->image ?? null,
            ],
        ];
    }
}
