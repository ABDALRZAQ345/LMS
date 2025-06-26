<?php

namespace App\Http\Controllers\User;

use App\Exceptions\ServerErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Quiz\createQuizRequest;
use App\Http\Requests\Quiz\DeleteQuizRequest;
use App\Http\Requests\Quiz\UpdateQuizRequest;
use App\Http\Resources\ContestResource;
use App\Models\Course;
use App\Models\Test;
use App\Models\User;
use App\Services\TestService;
use App\Services\User\TeacherService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TeacherController extends Controller
{
    protected TestService $testService;
    protected TeacherService $teacherService;

    public function __construct(TestService $testService, TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
        $this->testService = $testService;
    }

    public function courses(User $user): JsonResponse
    {
        $createdCourses = $this->teacherService->getTeacherCourses($user);

        return response()->json([
            'status' => true,
            'courses' => $createdCourses,
            'meta' => getMeta($createdCourses)
        ]);
    }

    public function learningPaths(User $user): JsonResponse
    {
        $createdLearningPaths = $this->teacherService->getTeacherLearningPaths($user);

        return response()->json([
            'status' => true,
            'learningPaths' => $createdLearningPaths,
            'meta' => getMeta($createdLearningPaths)
        ]);
    }

    public function contests(User $user): JsonResponse
    {
        $createdContests = $this->teacherService->getCreatedContests($user);

        return response()->json([
            'status' => true,
            'contests' => $createdContests,
            'meta' => getMeta($createdContests)
        ]);
    }

    public function myContests(): JsonResponse
    {
        $user = \Auth::user();
        $contests=$user->AllCreatedContests()->paginate(20);
        return response()->json([
            'status' => true,
            'contests' => ContestResource::collection($contests),
        ]);
    }

    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function createTest(CreateQuizRequest $request, Course $course): JsonResponse
    {
        $validated = $request->validated();
        $this->testService->createTest($course,$validated);
        return response()->json([
            'status' => true,
            'message' => 'test created successfully',
        ], 201);
    }

    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function updateTest(UpdateQuizRequest $request, Course $course, Test $test): JsonResponse
    {
        $validated = $request->validated();
        if($test->course_id!=$course->id){
            throw  new NotFoundHttpException();
        }
        $this->testService->UpdateTest($test,$validated);
        return response()->json([
            'status' => true,
            'message' => 'test updated successfully',
        ]);
    }

    public function deleteTest(DeleteQuizRequest $request,Course $course,Test $test): JsonResponse
    {

        if($test->course_id!=$course->id){
            throw  new NotFoundHttpException();
        }
        $this->testService->deleteTest($test);
        return response()->json([
            'status' => true,
            'message' => 'test deleted successfully',
        ]);

    }
}
