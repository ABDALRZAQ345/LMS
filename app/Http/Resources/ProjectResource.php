<?php

namespace App\Http\Resources;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data= parent::toArray($request);
        $data['user_name']=$data['user']['name'];
        $data['tag_name']=$data['tag']['name'];
        unset($data['tag']);
        unset($data['user']);
        return  $data;
    }
}
