<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\ServerErrorException;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\UpdateUserRequest;
use App\Http\Requests\GetUsersRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Responses\UserProfileResponse;
use App\Services\StreakService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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
    public function index(GetUsersRequest $request): AnonymousResourceCollection
    {
        $validated = $request->validated();

        return $this->userService->GetUsers($validated['friends'], $validated['role'], $validated['search'], $validated['orderBy'], $validated['direction']);

    }

    /**
     * @throws ServerErrorException
     */
    public function show(User $user): JsonResponse
    {
        return UserProfileResponse::response($user);

    }

    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $validated = $request->validated();

        $user = UserService::updateUser($validated);

        return response()->json([
            'status' => true,
            'user' => UserResource::make($user),
        ]);

    }
}
