<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubmissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this['id'],
            'problem_id' => $this['problem_id'],
            'problem_title' => $this['problem']['title'],
            'language' => $this['language'],
            'user_id' => $this['user_id'],
            'status' => $this['status'],
        ];
    }
}
