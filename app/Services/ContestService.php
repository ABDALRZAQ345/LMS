<?php

namespace App\Services;

use App\Exceptions\FORBIDDEN;
use App\Models\Contest;
use Illuminate\Support\Facades\Auth;

class ContestService
{
    public function getAllVerifiedContests(string $status = 'active', string $type = 'all')
    {
        $query = Contest::where('verified', true)
            ->where('status', $status);

        if ($type != 'all') {
            $query->where('type', $type);
        }

        return $query->paginate();
    }

    /**
     * @throws FORBIDDEN
     */
    public function GetContestContent(Contest $contest): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();
        $alreadyParticipate = $user->contests()->where('contest_id', $contest->id)->exists();

        if ($contest->type == 'quiz') {
            $questions = $contest->questions()
                ->with('options')
                ->get();

            return response()->json([
                'status' => true,
                'contest_type' => $contest->type,
                'alreadyParticipate' => $alreadyParticipate,
                'questions_count' => $questions->count(),
                'questions' => $questions,

            ]);
        } else {

            $problems = $contest->problems()->get();

            return response()->json([
                'status' => true,
                'contest_type' => $contest->type,
                'alreadyParticipate' => $alreadyParticipate,
                'problems_count' => $problems->count(),
                'problems' => $problems,

            ]);
        }

    }
}
