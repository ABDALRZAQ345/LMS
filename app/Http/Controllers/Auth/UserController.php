<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\ServerErrorException;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\UpdateUserRequest;
use App\Http\Requests\GetUsersRequest;
use App\Http\Resources\AchievementResource;
use App\Http\Resources\CertificateResource;
use App\Http\Resources\UserContestResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Responses\UserProfileResponse;
use App\Services\StreakService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Mockery\Exception;

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
        try {
            return $this->userService->GetUsers($validated['friends'], $validated['role'], $validated['search'], $validated['orderBy'], $validated['direction']);
        } catch (Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }

    /**
     * @throws ServerErrorException
     */
    public function show(User $user): JsonResponse
    {
        try {

            return UserProfileResponse::response($user);

        } catch (Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }

    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $validated = $request->validated();
        try {

            $user = UserService::updateUser($validated);

            return response()->json([
                'status' => true,
                'user' => UserResource::make($user),
            ]);
        } catch (Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }


}
