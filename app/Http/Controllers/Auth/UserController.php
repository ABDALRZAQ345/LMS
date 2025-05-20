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
        $this->CheckCanAccessStudent($user);

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

    public function getCurrentUser(): JsonResponse
    {
        $user = \Auth::user();

        return UserProfileResponse::response($user);
    }

    public function CheckCanAccessStudent(User $user): void
    {
        if ($user->role == 'admin' && \Auth::user()->role != 'admin') {
            throw new NotFoundHttpException;
        }
    }
}
