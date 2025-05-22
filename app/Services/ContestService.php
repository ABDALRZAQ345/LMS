<?php

namespace App\Services;

use App\Http\Resources\Courses\CourseResource;
use App\Http\Resources\LearningPaths\LearningPathResource;
use App\Models\Contest;
use App\Models\User;
use App\Repositories\Courses\CoursesRepository;
use App\Repositories\LearningPaths\LearningPathRepository;

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

    public function GetContestContent(Contest $contest)
    {
        if ($contest->type == 'quiz') {
            $questions = $contest->questions()
                ->with('options')
                ->get();
            return response()->json([
                'status' => true,
                'contest_type' => $contest->type,
                'questions_count' => $questions->count(),
                'questions' => $questions
            ]);
        } else {

            $problems = $contest->problems()->get();
            return response()->json([
                'status' => true,
                'contest_type' => $contest->type,
                'problems_count' => $problems->count(),
                'problems' => $problems
            ]);
        }


    }

}
