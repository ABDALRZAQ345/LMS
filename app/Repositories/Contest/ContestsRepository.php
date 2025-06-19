<?php

namespace App\Repositories\Contest;

use App\Models\Contest;
use App\Models\User;
use Carbon\Carbon;

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

    public function getAllComingContests()
    {
        $now = Carbon::now()->toDateTimeString();
        return Contest::where('request_status','accepted')
            ->where('start_at', '>', $now);
    }

    public function getAllActiveContests()
    {
        $now = Carbon::now()->toDateTimeString();
       return Contest::where('request_status','accepted')
            ->where('start_at', '<=', $now)
            ->whereRaw('DATE_ADD(start_at, INTERVAL time MINUTE) >= ?', [$now]);
    }

    public function getAllEndedContests()
    {
        $now = Carbon::now()->toDateTimeString();
       return Contest::where('request_status','accepted')
            ->whereRaw('DATE_ADD(start_at, INTERVAL time MINUTE) < ?', [$now]);

    }
}
