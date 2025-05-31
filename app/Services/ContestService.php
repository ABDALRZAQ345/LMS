<?php

namespace App\Services;

use App\Exceptions\ServerErrorException;
use App\Models\Contest;
use App\Repositories\Contest\ContestsRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContestService
{
    public  ContestsRepository $contestsRepository;

    public function __construct(ContestsRepository  $contestsRepository)
    {
        $this->contestsRepository = $contestsRepository;
    }

    public function getAllAcceptedContests(string $status = 'all', string $type = 'all', int $limit = 20)
    {
        $query = $this->contestsRepository->getAllAcceptedContests();
        if ($status != 'all') {
            $query->where('status', $status);
        }
        if ($type != 'all') {
            $query->where('type', $type);
        }

        return $query->paginate($limit);
    }

    public function getAllPendingContests(string $status = 'all', string $type = 'all', int $limit = 20)
    {
        $query = $this->contestsRepository->getAllPendingContests();
        if ($status != 'all') {
            $query->where('status', $status);
        }
        if ($type != 'all') {
            $query->where('type', $type);
        }

        return $query->paginate($limit);
    }

    public function GetContestContent(Contest $contest): JsonResponse
    {

        if ($contest->type == 'quiz') {
            return $this->GetContestQuestions($contest);
        } else if($contest->type == 'programming'){
            return $this->GetContestProblems($contest);
        }
        else{
            return response()->json([
               'status' => false,
            ]);
        }

    }

    public function GetContestQuestions(Contest $contest): JsonResponse
    {
        $user = Auth::user();
        $alreadyParticipate = $user->contests()->where('contest_id', $contest->id)->exists();

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

    }

    public function GetContestProblems(Contest $contest): JsonResponse
    {
        $user = Auth::user();
        $alreadyParticipate = $user->contests()->where('contest_id', $contest->id)->exists();

        $problems = $contest->problems()->get();

        return response()->json([
            'status' => true,
            'contest_type' => $contest->type,
            'alreadyParticipate' => $alreadyParticipate,
            'problems_count' => $problems->count(),
            'problems' => $problems,

        ]);
    }

    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function CreateContest($data): void
    {

        db::beginTransaction();
        try {
            $contest = Contest::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'time' => $data['time'],
                'type' => $data['type'],
                'status' => 'coming',
                'level' => $data['level'],
                'user_id' => Auth::id(),
                'start_at' => $data['start_at'],
            ]);
            foreach ($data['questions'] as $questionData) {
                $question = $contest->questions()->create([
                    'text' => $questionData['question'],
                ]);
                $correct = true;

                foreach ($questionData['options'] as $option) {
                    $question->options()->create([
                        'answer' => $option,
                        'is_correct' => $correct,
                    ]);
                    $correct = false;
                }
            }
            db::commit();

        } catch (\Exception $exception) {
            db::rollBack();
            throw new ServerErrorException($exception->getMessage());
        }

    }

    public function UpdateContestRequestStatus(Contest $contest, string $requestStatus): bool
    {
        return $contest->update([
            'request_status' => $requestStatus,
        ]);

    }
}
