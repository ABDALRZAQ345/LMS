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
use App\Responses\UserContestResponse;
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
        $achievements=$user->achievements()->paginate(20);
        return response()->json([
            'status' => true,
            'achievements' => AchievementResource::collection($achievements),
            'meta'=> getMeta($achievements)
        ]);

    }

    /**
     * @throws ServerErrorException
     */
    public function certificates(User $user): JsonResponse
    {

        $certificates=$user->certificates()
           // ->with('course')
            ->paginate(20);
        return response()->json([
            'status' => true,
            'certificates' => CertificateResource::collection($certificates),
            'meta'=> getMeta($certificates)
        ]);

    }

    /**
     * @throws ServerErrorException
     */
    public function contests(User $user): JsonResponse
    {
        return UserContestResponse::response($user);
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
        $projects=$this->projectService->GetUserProjects($user);
        return  response()->json([
           'status'=> true,
           'projects'=> ProjectResource::collection($projects),
            'meta' => getMeta($projects)
        ]);

    }
}
