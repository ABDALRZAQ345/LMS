<?php

namespace App\Http\Resources;

use Carbon\Carbon;
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

        $user=auth('api')->user();

        if(!$user || ( ( $user->role!='admin' &&  $data['teacher_id']!=$user->id) && isset($data['request_status']))){
            unset($data['request_status']);
        }
        else{
            $data['requested_at']=Carbon::parse($data['updated_at'])->toDateTimeString();
        }
        if($user){
            $alreadyParticipate = $user->contests()->where('contest_id', $this->id)->exists();
            $data['alreadyParticipate'] = $alreadyParticipate;
        }

        unset($data['user_id']);
        unset($data['updated_at']);
        return $data;
    }
}
