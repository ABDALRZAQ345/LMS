<?php

namespace App\Services;

use App\Exceptions\ServerErrorException;
use App\Models\Contest;
use App\Models\User;
use App\Repositories\Contest\ContestsRepository;
use Carbon\Carbon;
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

    public function getAllAcceptedContests(string $status = 'all', string $type = 'all',string $search='',int $limit = 20)
    {
        $query = $this->contestsRepository->getAllAcceptedContests()
            ->where('name','like','%'.$search.'%');
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
        $currentUser = $contest->students()->wherePivot('user_id', auth('api')->id())->orderByPivot('correct_answers','desc')
            ->first();

        return response()->json([
            'status' => true,
            'contest_type' => $contest->type,
            'alreadyParticipate' => $alreadyParticipate,
            'questions_count' => $questions->count(),
            'correct_answers' =>$currentUser ? $currentUser->pivot->correct_answers :0,
            'your_result' =>$currentUser ?getPercentage($currentUser->pivot->correct_answers,$questions->count(),true) : 0,
            'end_date' => Carbon::parse($contest->start_at)->addMinutes($contest->time)->toDateTimeString(),
            'questions' => $questions,

        ]);

    }

    public function GetContestProblems(Contest $contest): JsonResponse
    {
        $user = Auth::user();
        $alreadyParticipate = $user->contests()->where('contest_id', $contest->id)->exists();

        $problems = $contest->problems()
            ->select(['id','title','time_limit','memory_limit'])
            ->get();

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
    public function CreateQuizContest($data): void
    {

        db::beginTransaction();
        try {
            $contest = Contest::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'time' => $data['time'],
                'type' => 'quiz',
                'status' => 'coming',
                'level' => $data['level'],
                'user_id' => Auth::id(),
                'start_at' => $data['start_at'],
            ]);

            foreach ($data['questions'] as $questionData) {
                $question = $contest->questions()->create([
                    'text' => $questionData['question'],
                ]);


                foreach ($questionData['options'] as $option) {
                    $question->options()->create([
                        'answer' => $option['option'],
                        'is_correct' => $option['is_true'],
                    ]);

                }
            }
            db::commit();

        } catch (\Exception $exception) {
            db::rollBack();
            throw new ServerErrorException($exception->getMessage());
        }

    }
    //

    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function CreateProgrammingContest($data): void
    {

        db::beginTransaction();
        try {
            $contest = Contest::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'time' => $data['time'],
                'type' => 'programming',
                'status' => 'coming',
                'level' => $data['level'],
                'user_id' => Auth::id(),
                'start_at' => $data['start_at'],
            ]);
            foreach ($data['problems'] as $problemData) {
                 $contest->problems()->create([
                    'title'=>$problemData['title'],
                     'description'=>$problemData['description'],
                     'time_limit'=>$problemData['time_limit'],
                     'memory_limit'=>$problemData['memory_limit'],
                     'input' => $problemData['input'],
                     'output' => $problemData['output'],
                     'test_input'=>$problemData['test_input'],
                     'expected_output'=>$problemData['expected_output'],
                ]);
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

    public function GetContestResults(Contest $contest,$justFriends=false,$items=30): \Illuminate\Pagination\LengthAwarePaginator
    {
        if(!$justFriends || !Auth::user()){
          return  $contest->students()->paginate($items);
        }
        else{
            return $this->contestsRepository->friendsResults($contest,Auth::user())->paginate(25);
        }
    }
}
