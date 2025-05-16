<?php

namespace App\Http\Controllers;

use App\Exceptions\ServerErrorException;
use App\Http\Resources\AchievementResource;
use App\Http\Resources\CertificateResource;
use App\Http\Resources\UserContestResource;
use App\Models\User;
use App\Responses\StudentStaticsResponse;
use App\Services\StreakService;
use Illuminate\Http\JsonResponse;

class StudentController extends Controller
{
    protected StreakService $streakService;

    public function __construct(StreakService $streakService)
    {
        $this->streakService = $streakService;
    }

    /**
     * @throws ServerErrorException
     */
    public function achievements(User $user): JsonResponse
    {

        return response()->json([
            'status' => true,
            'achievements' => AchievementResource::collection($user->achievements()->get()),
        ]);

    }

    /**
     * @throws ServerErrorException
     */
    public function certificates(User $user): JsonResponse
    {

        return response()->json([
            'status' => true,
            'certificates' => CertificateResource::collection($user->certificates()->get()),
        ]);

    }

    /**
     * @throws ServerErrorException
     */
    public function contests(User $user): JsonResponse
    {

        $user->load('contests');

        return response()->json([
            'status' => true,
            'contests_count' => $user->contests()->count(),
            'total_points' => $user->points,
            'contests' => UserContestResource::collection($user->contests()->get()),
        ]);

    }

    /**
     * @throws ServerErrorException
     */
    public function streaks(User $user): JsonResponse
    {
        $streaks = $this->streakService->getUserStreaks($user);

        return response()->json([
            'status' => true,
            'streaks' => $streaks,
        ]);

    }

    /**
     * @throws ServerErrorException
     */
    public function statistics(User $user): JsonResponse
    {

       return  StudentStaticsResponse::response($user);

    }
}
