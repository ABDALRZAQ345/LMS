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
            'image' => $this->image,
            'gitHub_account' => $this->github_account,
            'bio' => $this->bio,
            'last_online' => $this->last_online,
            'role' => $this->role,
            'level' => $this->level,
            'joined'=> $this->created_at->format('Y-m-d')
        ];


        if ($this->role == 'student') {
            $data['points'] = $this->points;
            $data['role']=$this->role;
            $data['completed_courses'] = $this->finishedCourses()->count();
            $data['completed_learning_paths'] = $this->finishedLearningPaths()->count();
            $data['is_friend'] = db::table('friends')->where('user_id', \Auth::id())->where('friend_id', $data['id'])->exists() ? 1 : 0;
        }


        return $data;
    }
}
