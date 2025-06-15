<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContestResource;
use App\Models\User;
use App\Services\User\TeacherService;
use Illuminate\Http\JsonResponse;

class TeacherController extends Controller
{
    protected TeacherService $teacherService;

    public function __construct(TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
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
}
