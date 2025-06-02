<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAnyProject(User $user,Project $project): bool
    {
        if ($project->user_id == $user->id || $project->status == 'accepted' || $user->role == 'admin') {
                return  true;
        }
            return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function viewProject(User $user, Project $project): bool
    {
        if ($project->user_id == $user->id || $project->status == 'accepted' || $user->role == 'admin') {
            return  true;
        }
        return false;
    }

    public function createProject(User $user, Project $project): bool
    {
        return !$user->projects()->where('created_at', '>=', now()->startOfMonth())->count() >= 3;
    }


}
