<?php

namespace App\Http\Controllers\Contest;

use App\Exceptions\NotFoundException;
use App\Exceptions\ServerErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Contest\GetAllContestsRequest;
use App\Http\Requests\Contest\MakeContestRequest;
use App\Http\Requests\StandingRequest;
use App\Http\Resources\ContestResource;
use App\Http\Resources\StudentStandingResource;
use App\Models\Contest;
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
        $contests = $this->contestService->getAllAcceptedContests($validated['status'], $validated['type'],$validated['search']);

        return response()->json([
            'status' => true,
            'contests' => ContestResource::collection($contests),
            'meta' => getMeta($contests)
        ]);
    }

    /**
     * @throws NotFoundException
     * @throws AuthorizationException
     */
    public function show(Contest $contest): JsonResponse
    {
        \Gate::authorize('view', $contest);

        return response()->json([
            'status' => true,
            'contest' => ContestResource::make($contest->makeHidden('request_status')),
        ]);

    }


    /**
     * @throws AuthorizationException
     */
    public function questions(Contest $contest): JsonResponse
    {
        \Gate::authorize('view', $contest);

        return $this->contestService->GetContestQuestions($contest);
    }

    /**
     * @throws AuthorizationException
     */
    public function problems(Contest $contest): JsonResponse
    {
        \Gate::authorize('view', $contest);

        return $this->contestService->GetContestProblems($contest);
    }

    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function store(MakeContestRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $this->contestService->CreateContest($validated);

        return response()->json([
            'status' => true,
            'message' => 'Contest created successfully',
        ],201);
    }

    /**
     * @throws AuthorizationException
     */
    public function standing(StandingRequest $request,Contest $contest): JsonResponse
    {
        \Gate::authorize('seeStanding', $contest);
        $validated=$request->validated();

        $students =$this->contestService->GetContestResults($contest,$validated['justFriends']) ;

        return response()->json([
            'status' => true,
            'message' => "results might not be calculated yet  ",
            'students' => StudentStandingResource::collection($students),
            'meta' => getMeta($students)
        ]);

    }
}
