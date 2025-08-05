<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Http\Resources\Projects\ProjectResource;
use App\Jobs\SendFirebaseNotification;
use App\Models\Project;
use App\Models\User;
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
        if($project->status!='pending'){
            return response()->json([
                'status' => false,
                'message' => 'project already '.$project->status,
            ],400);
        }
        $project->update(['status' => 'accepted']);
        $student=$project->user;
        SendFirebaseNotification::dispatch($student," project accepted ",  "your project ".$project->title . "has been accepted");

        $this->achievementsService->ProjectAccepted($project);
        return response()->json([
            'status' => true,
            'message' => 'project Accepted successfully',
        ]);
    }

    public function reject(Project $project): JsonResponse
    {
        if($project->status!='pending'){
            return response()->json([
                'status' => false,
                'message' => 'project already '.$project->status,
            ],400);
        }
        $project->update(['status' => 'refused']);
        $student=$project->user;
        SendFirebaseNotification::dispatch($student," project rejected ",  "your project ".$project->title . "has been rejected");

        return response()->json([
            'status' => true,
            'message' => 'project Rejected successfully',
        ]);
    }
}
