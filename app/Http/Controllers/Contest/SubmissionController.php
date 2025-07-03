<?php

namespace App\Http\Controllers\Contest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contest\ShowProblemSubmissionsRequest;
use App\Http\Requests\Contest\SubmitContestRequest;
use App\Http\Requests\Contest\SubmitProblemRequest;
use App\Http\Resources\SubmissionResource;
use App\Models\Contest;
use App\Models\Problem;
use App\Services\ProblemService;
use App\Services\SubmissionService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

class SubmissionController extends Controller
{
    protected ProblemService $problemService;
    protected SubmissionService $submissionService;

    public function __construct(ProblemService $problemService, SubmissionService $submissionService)
    {
        $this->problemService = $problemService;
        $this->submissionService = $submissionService;
    }

    public function showProblemSubmissions(Contest $contest, Problem $problem, ShowProblemSubmissionsRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $problem = $contest->problems()->findOrFail($problem->id);

        $submissions = $this->problemService->getObjectSubmissions($problem, $validated['user_id'] ?? 'all', $validated['language'], $validated['status'],$validated['items']);

        return response()->json([
            'status' => true,
            'submissions' => SubmissionResource::collection($submissions),
            'meta' => getMeta($submissions),
        ]);


    }


    /**
     * @throws AuthorizationException
     */
    public function submitQuizContest(Contest $contest, SubmitContestRequest $request): JsonResponse
    {

        $validated = $request->validated();


        $percentage=$this->submissionService->SubmitQuizContest($validated,$contest);


        return response()->json([
            'status' => true,
            'message' => 'Contest submitted successfully' . ($contest->status == 'active') ? 'you can see your rank when the contest over' : '',
            'correct_answers' =>$percentage,
        ]);

    }



    public function showContestSubmissions(Contest $contest, ShowProblemSubmissionsRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $submissions = $this->problemService->getObjectSubmissions($contest, $validated['user_id'] ?? 'all', $validated['language'], $validated['status']);

        return response()->json([
            'status' => true,
            'submissions' => SubmissionResource::collection($submissions),
            'meta' => getMeta($submissions),
        ]);
    }

    public function submitProblem(Contest $contest, Problem $problem, SubmitProblemRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $problem = $contest->problems()->findOrFail($problem->id);

        $submission = $this->submissionService->createProblemSubmission($problem, $validated);

        return response()->json([
            'message' => 'Submission received',
            'submission_id' => $submission->id,
        ]);
    }

}
