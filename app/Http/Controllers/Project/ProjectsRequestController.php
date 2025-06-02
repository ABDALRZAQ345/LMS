<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Http\Resources\Projects\ProjectResource;
use App\Models\Project;
use App\Services\AchievementsService;
use App\Services\Project\ProjectService;
use Illuminate\Http\JsonResponse;

class ProjectsRequestController extends Controller
{
    protected ProjectService $projectService;
    protected  AchievementsService  $achievementsService;
    public function __construct(ProjectService $projectService, AchievementsService $achievementsService)
    {
        $this->projectService = $projectService;
        $this->achievementsService = $achievementsService;
    }

    public function requests(): JsonResponse
    {
        $projectsRequests = $this->projectService->getProjectsRequest();
        return response()->json([
            'status' => true,
            'message' => 'requests retried successfully',
            'requests' => ProjectResource::collection($projectsRequests),
            'meta' => getMeta($projectsRequests)
        ]);

    }

    public function accept(Project $project): JsonResponse
    {
        $project->update(['status' => 'accepted']);
        // todo send notification to student
        $this->achievementsService->ProjectAccepted($project);
        return response()->json([
            'status' => true,
            'message' => 'project Accepted successfully',
        ]);
    }

    public function reject(Project $project): JsonResponse
    {
        $project->update(['status' => 'refused']);
        // todo send notification to student
        return response()->json([
            'status' => true,
            'message' => 'project Rejected successfully',
        ]);
    }
}
