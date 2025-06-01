<?php

namespace App\Repositories\Contest;

use App\Models\Contest;
use App\Models\User;

class ContestsRepository
{
    public function getAllAcceptedContests()
    {
        return Contest::where('request_status', 'accepted');
    }

    public function getAllPendingContests()
    {
        return Contest::where('request_status', 'pending');
    }
    public function friendsResults(Contest $contest,User $user)
    {
        $friendIds = $user->friends()->pluck('users.id');
        return $contest->students()->whereIn('users.id', $friendIds);
    }
}
