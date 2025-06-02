<?php

namespace App\Http\Controllers\User;

use App\Exceptions\ServerErrorException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Achievements\AchievementResource;
use App\Http\Resources\CertificateResource;
use App\Http\Resources\Projects\ProjectResource;
use App\Http\Resources\Users\UserContestResource;
use App\Models\User;
use App\Responses\StudentStaticsResponse;
use App\Services\Project\ProjectService;
use App\Services\User\StreakService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class StudentController extends Controller
{
    protected StreakService $streakService;

    protected ProjectService $projectService;

    public function __construct(StreakService $streakService, ProjectService $projectService)
    {
        $this->streakService = $streakService;
        $this->projectService = $projectService;
    }

    /**
     * @throws ServerErrorException
     */
    public function achievements(User $user): JsonResponse
    {
        $user->load(['achievements' => fn($q) => $q->withPivot('created_at')]);

        return response()->json([
            'status' => true,
            'achievements' => AchievementResource::collection($user->achievements),
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

        return StudentStaticsResponse::response($user);

    }

    public function projects(User $user): JsonResponse
    {
        $projects=$this->projectService->GetUserProjects($user, \Auth::user());
        return  response()->json([
           'status'=> true,
           'projects'=> ProjectResource::collection($projects)
        ]);

    }
}
