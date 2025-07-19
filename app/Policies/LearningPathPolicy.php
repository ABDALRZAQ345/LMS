<?php

namespace App\Policies;

use App\Models\LearningPath;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LearningPathPolicy
{

    public function view(User $user, LearningPath $learningPath)
    {
        return $user->id === $learningPath->user_id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'teacher' ;
    }

    public function update(User $user, LearningPath $learningPath): bool
    {
        return $user->id === $learningPath->user_id;
    }

    public function delete(User $user, LearningPath $learningPath): bool
    {
        return $user->id === $learningPath->user_id;
    }

}
