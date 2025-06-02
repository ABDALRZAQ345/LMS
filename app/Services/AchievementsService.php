<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\Project;
use App\Models\User;

class AchievementsService {

    public function ProjectAccepted(Project $project): void
    {
        $user=User::find($project->user_id);
        if($user->AcceptedProjects()->count() == 3){
            $achievement=Achievement::where('name','Projects Master')->firstOrFail();
            $user->achievements()->attach($achievement->id);
        }
        else{
            $achievement=Achievement::where('name','Project Creator')->firstOrFail();
            $user->achievements()->attach($achievement->id);
        }

    }
    public function FriendAdded(User $user): void{

        if($user->Friends()->count() == 10){
            $achievement=Achievement::where('name','Friendly')->firstOrFail();
            $user->achievements()->syncWithoutDetaching($achievement->id);
        }
        else if($user->Friends()->count() == 100){
            $achievement=Achievement::where('name','Voyeur')->firstOrFail();
            $user->achievements()->syncWithoutDetaching($achievement->id);
        }

    }


}
