<?php

namespace App\Http\Resources\Projects;

use Carbon\Carbon;
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
        $data = parent::toArray($request);
        $data['user_name'] = $data['user']['name'];
        $data['user_image']= $data['user']['image'];
        $data['tag_name'] = $data['tag']['name'];
        unset($data['tag']);
        unset($data['user']);
        $data['requested_at']=Carbon::parse($data['updated_at'])->toDateTimeString();
        unset($data['created_at']);
        unset($data['updated_at']);
        return $data;
    }
}
