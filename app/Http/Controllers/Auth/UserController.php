<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\ServerErrorException;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\UpdateUserRequest;
use App\Http\Requests\Users\GetUsersRequest;
use App\Http\Resources\Users\UserResource;
use App\Models\User;
use App\Responses\UserProfileResponse;
use App\Services\User\StreakService;
use App\Services\User\UserService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends BaseController
{
    /**
     * @throws ServerErrorException
     */
    protected UserService $userService;

    protected StreakService $streakService;

    public function __construct(UserService $userService, StreakService $streakService)
    {

        $this->userService = $userService;
        $this->streakService = $streakService;
    }

    /**
     * @throws ServerErrorException
     */
    public function index(GetUsersRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $users =$this->userService->GetUsers($validated['friends'], $validated['role'], $validated['search'], $validated['orderBy'], $validated['direction'],$validated['items']);

        return response()->json([
            'status' => true,
            'message' => 'users retrieved successfully',
            'users' => UserResource::collection($users),
            'meta' => getMeta($users)
        ]);

    }

    /**
     * @throws ServerErrorException
     * @throws AuthorizationException
     */
    public function show(User $user): JsonResponse
    {
        \Gate::authorize('viewUser', $user);
        return UserProfileResponse::response($user);

    }

    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function update(UpdateUserRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = UserService::updateUser($validated);

        return response()->json([
            'status' => true,
            'user' => UserResource::make($user),
        ]);

    }

    public function getCurrentUser(): JsonResponse
    {
        $user = \Auth::user();
        return UserProfileResponse::response($user);
    }


}
