<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\Project\ProjectService;
use Illuminate\Http\JsonResponse;

class ProjectsRequestController extends Controller
{
    protected ProjectService $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }
    public function requests(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return $this->projectService->getProjectsRequest();
    }

    public function accept(Project $project): JsonResponse
    {
        $project->update([
            'status' => 'accepted',
        ]);
        // todo send notification to student
        return response()->json([
            'status' => true,
            'message' => 'project Accepted successfully',
        ]);
    }

    public function reject(Project $project): JsonResponse
    {
        $project->update([
            'status' => 'refused',
        ]);
        // todo send notification to student
        return response()->json([
            'status' => true,
            'message' => 'project Rejected successfully',
        ]);
    }
}
