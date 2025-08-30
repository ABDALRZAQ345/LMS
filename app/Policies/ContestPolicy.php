<?php

namespace App\Policies;

use App\Exceptions\BadRequestException;
use App\Exceptions\FORBIDDEN;
use App\Exceptions\NotFoundException;
use App\Models\Contest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ContestPolicy
{

    /**
     * Determine whether the user can view the model.
     * @throws FORBIDDEN
     * @throws NotFoundException
     */
    public function viewContest(?User $user, Contest $contest): bool
    {
        if($user &&($contest->user_id == $user->id || $user->role=='admin') ){
            return true;
        }
        if ($contest->request_status != 'accepted') {
            throw new NotFoundException();
        }

        if ($contest->status == 'coming') {
            throw new FORBIDDEN('coming contest not available , you can reach it when its active there is '.Carbon::parse($contest->start_at)->diffForHumans());
        }

        return true;
    }

    /**
     * @throws FORBIDDEN
     * @throws BadRequestException
     * @throws NotFoundException
     */
    public function submitContest(User $user, Contest $contest): bool
    {
        if ($contest->request_status != 'accepted') {
            throw new NotFoundException();
        }
        // check that the user already participated
        if ($user->contests()->where('contests.id', $contest->id)->exists()) {
            $contest = $user->contests()->find($contest->id);
            $correct = $contest->pivot['correct_answers'];
            throw new BadRequestException(' you already participated in this contest with '.$correct.' correct answer if you participate as official check your rank in standing');
        }
        if (Carbon::parse($contest->start_at) > Carbon::now())
        {
            throw new FORBIDDEN('coming contest not available , you can reach it when its active there is '.Carbon::parse($contest->start_at)->diffForHumans());
        }

        return true;
    }

    /**
     * @throws FORBIDDEN
     * @throws NotFoundException
     */
    public function seeStanding(?User $user, Contest $contest): bool
    {
        if ($contest->request_status != 'accepted') {
            throw new NotFoundException();
        }
        if ($contest->status == 'coming') {
            throw new FORBIDDEN('coming contest not available , you can reach it when its active there is '.Carbon::parse($contest->start_at)->diffForHumans());
        }
        return  true;

    }
}
