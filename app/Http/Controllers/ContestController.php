<?php

namespace App\Http\Controllers;

use App\Exceptions\FORBIDDEN;
use App\Exceptions\NotFoundException;
use App\Http\Requests\GetAllContestsRequest;
use App\Http\Resources\ContestResource;
use App\Models\Contest;
use App\Services\ContestService;
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
        $contests = $this->contestService->getAllVerifiedContests($validated['status'], $validated['type']);

        return response()->json([
            'status' => true,
            'contests' => ContestResource::collection($contests),
        ]);
    }

    /**
     * @throws NotFoundException
     */
    public function show(Contest $contest): JsonResponse
    {
        if ($contest->verified) {
            return response()->json([
                'status' => true,
                'contest' => ContestResource::make($contest),
            ]);
        }
        throw new NotFoundException;
    }

    public function content(Contest $contest): JsonResponse
    {
        \Gate::authorize('view',$contest);
        return $this->contestService->GetContestContent($contest);
    }

    /**
     * @throws NotFoundException
     */
    public function questions(Contest $contest): JsonResponse
    {
        \Gate::authorize('view',$contest);
        return $this->contestService->GetContestContent($contest);
    }

    /**
     * @throws NotFoundException
     */
    public function problems(Contest $contest): JsonResponse
    {
        \Gate::authorize('view',$contest);
        return $this->contestService->GetContestContent($contest);
    }
}
