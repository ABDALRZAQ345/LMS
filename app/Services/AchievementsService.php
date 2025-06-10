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
            $user->achievements()->syncWithoutDetaching($achievement->id);
        }
        else{
            $achievement=Achievement::where('name','Project Creator')->firstOrFail();
            $user->achievements()->syncWithoutDetaching($achievement->id);
        }

    }
    public function FriendAdded(User $user): void{

        if($user->Friends()->count() == 10){
            $achievement=Achievement::where('name','Friendly')->firstOrFail();
            $user->achievements()->syncWithoutDetaching($achievement->id);
        }
        else if($user->Friends()->count() == 100){
            $achievement=Achievement::where('name','Famous')->firstOrFail();
            $user->achievements()->syncWithoutDetaching($achievement->id);
        }

    }

    public function ReachIntermediate(User $user): void{
        $achievement=Achievement::where('name','Not beginner yet')->firstOrFail();
        $user->achievements()->syncWithoutDetaching($achievement->id);
    }

    public function FirstContest(User $user): void{
        $achievement=Achievement::where('name','Contest Rookie')->firstOrFail();
        $user->achievements()->syncWithoutDetaching($achievement->id);
    }

    public function ReturnAfterYear(User $user): void{
        $achievement=Achievement::where('name','Back Again')->firstOrFail();
        $user->achievements()->syncWithoutDetaching($achievement->id);
    }



}
