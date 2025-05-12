<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\ServerErrorException;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Auth\UpdateUserRequest;
use App\Http\Resources\AchievementResource;
use App\Http\Resources\CertificateResource;
use App\Http\Resources\UserContestResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Responses\UserProfileResponse;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class UserController extends BaseController
{
    /**
     * @throws ServerErrorException
     */
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
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
    public function update(UpdateUserRequest $request,User $user): JsonResponse
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

    /**
     * @throws ServerErrorException
     */
    public function achievements(User $user): JsonResponse
    {
        try {

            return response()->json([
                'status' => true,
                'achievements' => AchievementResource::collection($user->achievements()->get()),
            ]);
        } catch (Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }

    /**
     * @throws ServerErrorException
     */
    public function certificates(User $user): JsonResponse
    {
        try {
            return response()->json([
                'status' => true,
                'certificates' => CertificateResource::collection($user->certificates()->get()),
            ]);
        } catch (Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }


    }

    /**
     * @throws ServerErrorException
     */
    public function contests(User $user): JsonResponse
    {
        try {
            $user->load('contests');
            return response()->json([
                'status' => true,
                'contests_count' => $user->contests()->count(),
                'total_points' =>   $user->points,
                'contests' => UserContestResource::collection($user->contests()->get()),
            ]);
        } catch (Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }
}
