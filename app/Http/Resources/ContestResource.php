<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       $data=parent::toArray($request);
       $data['teacher_id']=$data['user_id'];
       unset($data['user_id'],$data['verified']);
       return $data;
    }
}
