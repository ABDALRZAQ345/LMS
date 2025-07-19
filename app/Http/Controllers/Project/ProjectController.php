<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Projects\AddProjectRequest;
use App\Http\Requests\Projects\GetProjectsRequest;
use App\Http\Resources\Projects\ProjectResource;
use App\Models\Project;
use App\Models\Tag;
use App\Services\Project\ProjectService;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    protected ProjectService $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    public function index(GetProjectsRequest $request): JsonResponse
    {

        $validated = $request->validated();
        $projects = $this->projectService->GetAllProjects($validated);
        return response()->json([
            'status' => true,
            'projects' => ProjectResource::collection($projects),
            'meta' => getMeta($projects)
        ]);

    }

    /**
     * @throws AuthorizationException
     */
    public function show(Project $project): JsonResponse
    {
        \Gate::authorize('viewProject', $project);
        return  response()->json([
            'status' => true,
            'project' => $this->projectService->getProject($project),
        ]);

    }

    public function store(AddProjectRequest $request): JsonResponse
    {
        $validated = $request->validated();

        return $this->projectService->AddProject($validated);
    }

    public function getTags(): JsonResponse
    {
        return response()->json([
            'status' => true,
            'tags' => Tag::all(),
        ]);
    }


    public function delete(Project $project): JsonResponse
    {
        $project->delete();

        return response()->json([
            'status' => true,
            'message' => 'Project successfully deleted',
        ]);
    }
}
