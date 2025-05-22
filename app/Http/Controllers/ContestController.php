<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundException;
use App\Http\Requests\GetAllContestsRequest;
use App\Http\Resources\ContestResource;
use App\Models\Contest;
use App\Services\ContestService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ContestController extends Controller
{
    protected ContestService $contestService;
    public function __construct(ContestService $contestService)
    {
        $this->contestService = $contestService;
    }
    public function index(GetAllContestsRequest $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validated();
       $contests = $this->contestService->getAllVerifiedContests($validated['status'],$validated['type']);
        return response()->json([
            'status' => true,
            'contests' => ContestResource::collection($contests)
        ]);
    }

    /**
     * @throws NotFoundException
     */
    public function show(Contest $contest): \Illuminate\Http\JsonResponse
    {
        if($contest->verified)
        {
            return response()->json([
                'status' => true,
                'contest' => ContestResource::make($contest)
            ]);
        }
        throw new NotFoundException();
    }

    public function content(Contest $contest): \Illuminate\Http\JsonResponse
    {
        return $this->contestService->GetContestContent($contest);
    }

    /**
     * @throws NotFoundException
     */
    public function questions(Contest $contest): \Illuminate\Http\JsonResponse
    {

        return $this->contestService->GetContestContent($contest);

    }

    /**
     * @throws NotFoundException
     */
    public function problems(Contest $contest): \Illuminate\Http\JsonResponse
    {

            return $this->contestService->GetContestContent($contest);

    }

}
