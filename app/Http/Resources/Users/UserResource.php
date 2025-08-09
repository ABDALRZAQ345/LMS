<?php

namespace App\Http\Resources\Users;

use App\Models\User;
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
        $data = [
            'id'=> $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'image' => getPhoto($this->image),
            'gitHub_account' => $this->gitHub_account,
            'bio' => $this->bio,
            'last_online' => $this->last_online,
            'role' => $this->role,
            'joined'=> $this->created_at->format('Y-m-d'),
            'age' => $this->age,

        ];


        if ($this->role == 'student') {
            $data['points'] = $this->points;
            $data['role']=$this->role;
            $data['level']=$this->level;
            $data['blocked']= !$this->active;
            $data['current_streak']=  $this->CurrentStreak();
            $data['completed_courses'] = $this->finishedCourses()->count();
            $data['completed_learning_paths'] = $this->finishedLearningPaths()->count();
            if(auth('api')->user() != null)
            $data['is_friend'] = db::table('friends')->where('user_id', auth('api')->id())->where('friend_id', $data['id'])->exists() ? 1 : 0;

        }
        if($this->role == 'teacher'){
            $data['created_courses']= $this->verifiedCourses()->count();
            $data['created_paths']=$this->verifiedLearningPaths()->count();
            $data['created_contests']=$this->AcceptedCreatedContests()->count();
            }


        return $data;
    }
}
