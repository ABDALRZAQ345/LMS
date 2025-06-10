<?php

namespace App\Services\Project;

use App\Http\Resources\Projects\ProjectResource;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProjectService
{
    public function GetAllProjects($data)
    {

        if ($data['tag'] == 'all') {
            $projects = Project::where('title', 'like', '%'.$data['search'].'%');
        } else {
            $projects = Project::whereHas('tag', function ($query) use ($data) {
                $query->where('name', 'like', '%'.$data['tag'].'%');
            })->where('title', 'like', '%'.$data['search'].'%');
        }

        return $projects->with(['user', 'tag'])->where('status', 'accepted')->paginate(20);

    }

    public function getProject(Project $project)
    {

            $project->load(['user', 'tag']);

            return new ProjectResource($project);

    }

    public function GetUserProjects(User $user, ?User $currentUser): \Illuminate\Database\Eloquent\Collection
    {
        if ($currentUser && ( $currentUser->id == $user->id || $currentUser->role == 'admin')) {
            return $user->projects()
                ->with(['user', 'tag'])->get();
        } else {
            return $user->projects()->where('status', 'accepted')
                ->with(['user', 'tag'])->get();
        }

    }

    public function AddProject($data): JsonResponse
    {
        $user = Auth::user();
        if ($this->checkCanAddProject($user)) {
            return response()->json([
                'status' => false,
                'message' => 'you cant send more than 3 projects requests a month ',
            ]);
        }
        $user->projects()->create($data);

        return response()->json([
            'status' => true,
            'message' => 'project added successfully',
        ]);
    }

    public function getProjectsRequest()
    {
        return Project::where('status', 'pending')->With(['user', 'tag'])->paginate(20);

    }

    /**
     * @param  User|\Illuminate\Contracts\Auth\Authenticatable|(User&\Illuminate\Contracts\Auth\Authenticatable)|null  $user
     */
    public function checkCanAddProject(User $user): bool
    {
        return $user->projects()->where('created_at', '>=', now()->startOfMonth())->count() >= 3;
    }
}
