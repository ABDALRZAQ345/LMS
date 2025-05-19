<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddProjectRequest;
use App\Http\Requests\GetProjectsRequest;
use App\Http\Requests\updateProjectStatusRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\Tag;
use App\Services\Project\ProjectService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
            'tags' => Tag::all()
        ]);
    }

    public function requests()
    {
        return $this->projectService->getProjectsRequest();
    }

    public function updateStatus(updateProjectStatusRequest $request,Project $project)
    {
        $validated = $request->validated();
        return $this->projectService->UpdateProjectStatus($validated,$project);

    }
}
