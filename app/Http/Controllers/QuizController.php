<?php

namespace App\Http\Controllers;

use App\Exceptions\NotFoundException;
use App\Exceptions\ServerErrorException;
use App\Http\Requests\SubmitTestRequest;
use App\Http\Resources\TestResource;
use App\Models\Course;
use App\Models\Test;
use App\Services\SubmissionService;
use Illuminate\Http\JsonResponse;

class QuizController extends Controller
{
    /**
     * @throws NotFoundException
     */
    protected SubmissionService  $submissionService;
    public function __construct(SubmissionService $submissionService){
        $this->submissionService = $submissionService;
    }

    public function showTest(Course $course, Test $test): JsonResponse
    {
        if ($test->course_id != $course->id) throw new NotFoundException();

        $currentUser = $test->students()->wherePivot('user_id', auth('api')->id())->orderByPivot('correct_answers','desc')
            ->first();
        $test->load('questions.options');
        $test->loadCount('questions');

        return response()->json([
            'status' => true,
            'message' => 'test  received successfully',
            'already_taken' => $currentUser!=null,
            'best_result' => $currentUser ?getPercentage($currentUser->pivot->correct_answers,$test->questions_count) : "0%",
            'test' => TestResource::make($test)
        ]);
    }


    /**
     * @throws ServerErrorException
     * @throws \Throwable
     * @throws NotFoundException
     */
    public function SubmitTest(SubmitTestRequest $request, Course $course, Test $test): JsonResponse
    {
        $validated=$request->validated();
        if ($test->course_id != $course->id) throw new NotFoundException();
        $percentage=$this->submissionService->submitTest($validated,$test);
        return response()->json([
            'status' => true,
            'message' => $percentage >= 60 ? "test success " : "test failed you should gain more than 60%",
            'correct_answers' =>$percentage.'%',
        ]);
    }
}
