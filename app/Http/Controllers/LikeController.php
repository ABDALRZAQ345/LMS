<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\LikeService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    protected LikeService $likeService;

    public function __construct(LikeService $likeService)
    {
        $this->likeService = $likeService;
    }

    /**
     * @throws \Throwable
     */
    public function LikeProject(project $project): \Illuminate\Http\JsonResponse
    {
        \Gate::authorize('viewProject', $project);
        return $this->likeService->Liked($project, \Auth::user());

    }

    /**
     * @throws \Throwable
     * @throws AuthorizationException
     */
    public function DeleteProjectLike(project $project): \Illuminate\Http\JsonResponse
    {
        \Gate::authorize('viewProject', $project);
        return  $this->likeService->DeleteLike($project, \Auth::user());
    }
}
