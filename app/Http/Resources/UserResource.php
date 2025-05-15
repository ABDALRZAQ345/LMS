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
        $data['last_online'] = Carbon::parse($data['last_online'])->diffForHumans();
        $data['joined'] = Carbon::parse($data['created_at'])->format('Y-m-d');
        unset($data['created_at']);
        if($data['role'] == 'student'){
            $data['completed_courses'] = db::table('course_user')->where('user_id', $data['id'])->where('status', 'finished')->count();
            //todo
            //$data['completed_learning_paths'];
                    }
        if ($data['role'] == 'teacher') {
            unset($data['level']);
            unset($data['points']);
        }

        return $data;
    }
}
