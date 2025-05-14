<?php

namespace App\Http\Controllers;

use App\Exceptions\ServerErrorException;
use App\Http\Resources\AchievementResource;
use App\Http\Resources\CertificateResource;
use App\Http\Resources\UserContestResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery\Exception;

class StudentController extends Controller
{
    /**
     * @throws ServerErrorException
     */
    public function achievements(User $user): JsonResponse
    {
        try {

            return response()->json([
                'status' => true,
                'achievements' => AchievementResource::collection($user->achievements()->get()),
            ]);
        } catch (Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }

    /**
     * @throws ServerErrorException
     */
    public function certificates(User $user): JsonResponse
    {
        try {
            return response()->json([
                'status' => true,
                'certificates' => CertificateResource::collection($user->certificates()->get()),
            ]);
        } catch (Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }

    /**
     * @throws ServerErrorException
     */
    public function contests(User $user): JsonResponse
    {
        try {
            $user->load('contests');

            return response()->json([
                'status' => true,
                'contests_count' => $user->contests()->count(),
                'total_points' => $user->points,
                'contests' => UserContestResource::collection($user->contests()->get()),
            ]);
        } catch (Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }

    /**
     * @throws ServerErrorException
     */
    public function streaks(User $user): JsonResponse
    {
        try {
            $streaks = $this->streakService->getUserStreaks($user);

            return response()->json([
                'status' => true,
                'streaks' => $streaks,
            ]);
        } catch (Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function statistics(User $user): JsonResponse
    {
        try {
            return response()->json([
                'status' => true,
                'completed_courses' => $user->finishedCourses()->count(),
                'certificates' => $user->certificates()->count(),
                'contests' => $user->contests()->count(),
                'points' => $user->points,
                'achievements' => $user->achievements()->count(),
                'best_contest' => new UserContestResource($user->contests()->orderByPivot('rank', 'desc')->without('pivot')->first()),
                'max_streak' => $user->LongestStreak(),
                'current_streak' => $user->CurrentStreak(),
            ]);
        } catch (Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }
}
