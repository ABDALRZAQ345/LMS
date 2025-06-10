<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function viewUser(?User $user, User $accessedUser): bool
    {

        if ($accessedUser->role == 'admin' &&$user &&  $user->role != 'admin') {
            return false;
        }
        return true;

    }


}
