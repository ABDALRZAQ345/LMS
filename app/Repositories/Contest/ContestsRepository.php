<?php

namespace App\Repositories\Contest;

use App\Models\Contest;

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
}
