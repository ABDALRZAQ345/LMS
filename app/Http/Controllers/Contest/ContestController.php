<?php

namespace App\Http\Controllers\Contest;

use App\Exceptions\ServerErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Contest\GetAllContestsRequest;
use App\Http\Requests\Contest\MakeContestRequest;
use App\Http\Requests\Contest\MakeProgrammingContestRequest;
use App\Http\Requests\Contest\ShowContestProblemsRequest;
use App\Http\Requests\Contest\ShowContestRequest;
use App\Http\Requests\Contest\StandingRequest;
use App\Http\Requests\ShowQuestionsRequest;
use App\Http\Resources\ContestResource;
use App\Models\Contest;
use App\Responses\ContestStandingResponse;
use App\Services\ContestService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

class ContestController extends Controller
{
    protected ContestService $contestService;

    public function __construct(ContestService $contestService)
    {
        $this->contestService = $contestService;
    }

    public function index(GetAllContestsRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $contests = $this->contestService->getAllAcceptedContests($validated['status'], $validated['type'], $validated['search']);

        return response()->json([
            'status' => true,
            'contests' => ContestResource::collection($contests),
            'meta' => getMeta($contests)
        ]);
    }


    public function show(Contest $contest, ShowContestRequest $request): JsonResponse
    {
        return response()->json([
            'status' => true,
            'contest' => ContestResource::make($contest),
        ]);

    }


    /**
     * @throws AuthorizationException
     */
    public function questions(Contest $contest, ShowQuestionsRequest $request): JsonResponse
    {

        return $this->contestService->GetContestQuestions($contest);
    }

    /**
     * @throws AuthorizationException
     */
    public function problems(Contest $contest,ShowContestProblemsRequest $request): JsonResponse
    {
        return $this->contestService->GetContestProblems($contest);
    }

    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function CreateQuizContest(MakeContestRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $this->contestService->CreateQuizContest($validated);

        return response()->json([
            'status' => true,
            'message' => 'Contest created successfully',
        ], 201);
    }

    /**
     * @throws AuthorizationException
     */
    public function standing(StandingRequest $request, Contest $contest): JsonResponse
    {

        $validated = $request->validated();
        $students = $this->contestService->GetContestResults($contest, $validated['justFriends']);
        $currentUser = $students->where('id', \Auth::id())->first();

        return ContestStandingResponse::response($students,$currentUser);

    }

    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function CreateProgrammingContest(MakeProgrammingContestRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $this->contestService->CreateProgrammingContest($validated);

        return response()->json([
            'status' => true,
            'message' => 'Contest created successfully',
        ], 201);
    }
}
