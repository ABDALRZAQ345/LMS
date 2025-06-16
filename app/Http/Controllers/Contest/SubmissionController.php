<?php

namespace App\Http\Controllers\Contest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contest\SubmitContestRequest;
use App\Http\Requests\Contest\SubmitProblemRequest;
use App\Http\Requests\ShowProblemSubmissionsRequest;
use App\Http\Resources\StudentStandingCollection;
use App\Http\Resources\StudentStandingResource;
use App\Http\Resources\SubmissionResource;
use App\Jobs\ProcessSubmission;
use App\Models\Contest;
use App\Models\Problem;
use App\Models\Submission;
use App\Responses\StudentStaticsResponse;
use App\Services\ProblemService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class SubmissionController extends Controller
{
    protected ProblemService $problemService;

    public function __construct(ProblemService $problemService)
    {
        $this->problemService = $problemService;
    }

    public function showProblemSubmissions(Contest $contest, Problem $problem, ShowProblemSubmissionsRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $problem = $contest->problems()->findOrFail($problem->id);
        $submissions = $this->problemService->getObjectSubmissions($problem, $validated['user_id'] ?? 'all', $validated['language'], $validated['status']);

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

        $submission = Submission::create([
            'problem_id' => $problem->id,
            'language' => $validated['language'],
            'code' => $validated['code'],
            'status' => 'pending',
            'user_id' => Auth::id()
        ]);

        ProcessSubmission::dispatch($submission);

        return response()->json([
            'message' => 'Submission received',
            'submission_id' => $submission->id,
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function submitQuizContest(Contest $contest, SubmitContestRequest $request): JsonResponse
    {

        $validated = $request->validated();

        $questions = $contest->questions()->get();
        $questionsCount = $questions->count();
        $correct = $this->getNumberOfCorrectAnswers($validated['answers'], $questions, $contest);

        return response()->json([
            'status' => true,
            'message' => 'Contest submitted successfully' . ($contest->status == 'active') ? 'you can see your rank when the contest over' : '',
            'correct_answers' => getPercentege($correct, $questionsCount),
        ]);

    }

    public function getNumberOfCorrectAnswers($answers, \Illuminate\Database\Eloquent\Collection $questions, Contest $contest): int
    {

        $correct = 0;
        foreach ($questions as $question) {
            $correctOption = $question->correctOption();
            if (isset($answers[$question->id]) && $correctOption && $answers[$question->id] == $correctOption->id) {
                $correct++;
            }
        }
        db::table('contest_user')->insert([
            'end_time' => now(), 'correct_answers' => $correct,
            'user_id' => Auth::user()->id,
            'contest_id' => $contest->id,
            'is_official' => $contest->status == 'active',
        ]);

        return $correct;
    }

    public function showContestSubmissions(Contest $contest, ShowProblemSubmissionsRequest $request): JsonResponse
    {
        $validated=$request->validated();
        $submissions = $this->problemService->getObjectSubmissions($contest,$validated['user_id'] ?? 'all',$validated['language'],$validated['status']);

        return response()->json([
            'status' => true,
            'submissions' => SubmissionResource::collection($submissions),
            'meta' => getMeta($submissions),
        ]);
    }

}
