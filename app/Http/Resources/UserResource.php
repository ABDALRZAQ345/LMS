<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);
        $time=Carbon::parse($data['last_online'])->addMinutes(10);

        if(now()  <= $time){
            $data['last_online']= 'online';
        }
        else {
            $data['last_online']= $time->diffForHumans();
        }
        $data['joined'] = Carbon::parse($data['created_at'])->format('Y-m-d');
        unset($data['created_at']);
        if ($data['role'] == 'student') {

            $data['completed_courses'] = db::table('course_user')->where('user_id', $data['id'])->where('status', 'finished')->count();
            // todo
            // $data['completed_learning_paths'];
            if($data['id'] !=\Auth::id())
             $data['is_friend']=db::table('friends')->where('user_id',\Auth::id())->where('friend_id' ,$data['id'])->count();
        }
        if ($data['role'] == 'teacher') {
            unset($data['level']);
            unset($data['points']);
        }

        return $data;
    }
}
