<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class ContestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);
        $data['teacher_id'] = $data['user_id'];

        $user=\Auth::user();

        if(!$user || ( ( $user->role!='admin' &&  $data['teacher_id']!=$user->id) && isset($data['request_status']))){
            unset($data['request_status']);
        }
        unset($data['user_id']);
        return $data;
    }
}
