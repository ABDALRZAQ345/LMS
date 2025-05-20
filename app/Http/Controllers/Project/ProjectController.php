<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Projects\AddProjectRequest;
use App\Http\Requests\Projects\GetProjectsRequest;
use App\Http\Requests\Projects\updateProjectStatusRequest;
use App\Http\Resources\Projects\ProjectResource;
use App\Models\Project;
use App\Models\Tag;
use App\Services\Project\ProjectService;

class ProjectController extends Controller
{
    protected ProjectService $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    public function index(GetProjectsRequest $request)
    {
        $validated = $request->validated();

        return ProjectResource::collection($this->projectService->GetAllProjects($validated));
    }

    public function show(Project $project)
    {
        return $this->projectService->getProject($project);
    }

    public function store(AddProjectRequest $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validated();

        return $this->projectService->AddProject($validated);
    }

    public function getTags(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => true,
            'tags' => Tag::all(),
        ]);
    }

    public function requests()
    {
        return $this->projectService->getProjectsRequest();
    }

    public function updateStatus(updateProjectStatusRequest $request, Project $project)
    {
        $validated = $request->validated();

        return $this->projectService->UpdateProjectStatus($validated, $project);

    }

    public function delete(Project $project)
    {
        $project->delete();

        return response()->json([
            'status' => true,
            'message' => 'Project successfully deleted',
        ]);
    }
}
