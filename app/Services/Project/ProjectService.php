<?php

namespace App\Services\Project;

use App\Http\Resources\Projects\ProjectResource;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ProjectService
{
    public function GetAllProjects($data)
    {

        if (!empty($data['search'])) {

            return $this->getAlldataMethod($data);
        } else {

            return Cache::remember('projects' . $data['tag']??"" . $data['page']??"" . $data['items']??"", 60 * 30, function () use ($data) {
               return $this->getAlldataMethod($data);
            });
        }



    }

    public function getProject(Project $project): ProjectResource
    {

            $project->load(['user', 'tag']);

            return new ProjectResource($project);

    }

    public function GetUserProjects(User $user,$data): \Illuminate\Pagination\LengthAwarePaginator
    {
        $currentUser=Auth::user();
        if ($currentUser && ( $currentUser->id == $user->id || $currentUser->role == 'admin')) {
            return $user->projects()
                ->where('title', 'like', '%'.$data['search'].'%')
                ->with(['user', 'tag'])->paginate(20);
        } else {
            return $user->projects()->where('status', 'accepted')
                ->where('title', 'like', '%'.$data['search'].'%')
                ->with(['user', 'tag'])->paginate(20);
        }

    }

    public function AddProject($data): JsonResponse
    {
        $user = Auth::user();
        if ($this->checkCanAddProject($user)) {
            return response()->json([
                'status' => false,
                'message' => 'you can`t send more than 3 projects requests a month ',
            ]);
        }
        if (isset($data['image']) && $data['image'] != null)
            $data['image']= NewPublicPhoto($data['image']);

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

    /**
     * @param $data
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAlldataMethod($data): \Illuminate\Pagination\LengthAwarePaginator
    {
        if ($data['tag'] == 'all') {
            $projects = Project::query();
        } else {
            $projects = Project::whereHas('tag', function ($query) use ($data) {
                $query->where('name', 'like', '%' . $data['tag'] . '%');
            });
        }

        return $projects->where('title', 'like', '%' . $data['search'] . '%')
            ->with(['user', 'tag'])
            ->where('status', 'accepted')
            ->orderByDesc('likes')
            ->paginate($data['items']);
    }
}
