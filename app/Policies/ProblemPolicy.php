<?php

namespace App\Policies;

use App\Exceptions\BadRequestException;
use App\Exceptions\FORBIDDEN;
use App\Models\Problem;
use App\Models\User;
use App\Services\SubmissionService;
use Carbon\Carbon;

class ProblemPolicy
{
    protected SubmissionService  $submissionService;
    /**
     * Create a new policy instance.
     * @throws FORBIDDEN
     */
    public function __construct(SubmissionService $submissionService)
    {
        $this->submissionService = $submissionService;
    }

    /**
     * @throws FORBIDDEN
     * @throws BadRequestException
     */
    public function submitProblem(User $user, Problem $problem): bool
    {
        $contest=$problem->contest;
        if ($contest->request_status != 'accepted') {
            return false;
        }
        if($this->submissionService->CheckUserSolvedProblem($user,$problem)){
            throw new BadRequestException(' you already solved this problem ');

        }
        if ($contest->status == 'coming') {
            throw new FORBIDDEN('coming contest not available , you can reach it when its active there is '.Carbon::parse($contest->start_at)->diffForHumans());
        }
        return  true;
}
}
