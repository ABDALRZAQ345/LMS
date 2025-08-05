<?php

namespace App\Http\Controllers\Contest;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContestResource;
use App\Jobs\SendFirebaseNotification;
use App\Models\Contest;
use App\Services\ContestService;
use Illuminate\Http\JsonResponse;

class ContestsRequestController extends Controller
{
    protected ContestService $contestService;

    public function __construct(ContestService $contestService)
    {
        $this->contestService = $contestService;
    }

    public function requests(): JsonResponse
    {
        $contests = $this->contestService->getAllPendingContests();
        return response()->json([
            'status' => true,
            'message' => "requests retried successfully",
            'contests' => ContestResource::collection($contests),
            'meta' => getMeta($contests)
        ]);
    }

    public function accept(Contest $contest): JsonResponse
    {
        $this->contestService->UpdateContestRequestStatus($contest, 'accepted');
        $teacher=$contest->teacher;
        SendFirebaseNotification::dispatch($teacher, "contest accepted", " your contest ". $contest->name . " has been accepted");

        return response()->json([
            'status' => true,
            'message' => 'Contest accepted successfully',
        ]);
    }

    public function reject(Contest $contest): JsonResponse
    {

        $this->contestService->UpdateContestRequestStatus($contest, 'rejected');
        $teacher=$contest->teacher;
        SendFirebaseNotification::dispatch($teacher, "contest rejected", " your contest ". $contest->name . " has been rejected");

        return response()->json([
            'status' => true,
            'message' => 'Contest rejected successfully',
        ]);

    }
}
