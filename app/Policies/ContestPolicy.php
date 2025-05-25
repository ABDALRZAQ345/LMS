<?php

namespace App\Policies;

use App\Exceptions\BadRequestException;
use App\Exceptions\FORBIDDEN;
use App\Models\Contest;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ContestPolicy
{
    /**
     * Determine whether the user can view any models.
     * @throws FORBIDDEN
     */
    public function viewAny(User $user,Contest $contest): bool
    {
        if($contest->verified==false)
            return false;

        if($contest->status=='coming')
            throw new FORBIDDEN("coming contest not available , you can reach it when its active or ended");

        return  true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Contest $contest): bool
    {
        if($contest->verified == false)
            return false;

        if($contest->status=='coming')
            throw new FORBIDDEN("coming contest not available , you can reach it when its active or ended");

        return  true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Contest $contest): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Contest $contest): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Contest $contest): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Contest $contest): bool
    {
        return false;
    }

    /**
     * @throws FORBIDDEN
     * @throws BadRequestException
     */
    public function submit(User $user, Contest $contest): bool
    {
        if($contest->verified == false)
            return false;

        if ($user->contests()->where('contests.id', $contest->id)->exists()) {
            throw  new BadRequestException("you already participated in this contest");
        }
        if ($contest->status == 'coming')
        {
            throw new FORBIDDEN("coming contest not available , you can reach it when its active or ended");
        }
        return true;
    }
}
