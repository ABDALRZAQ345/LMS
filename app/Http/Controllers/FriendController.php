<?php

namespace App\Http\Controllers;

use App\Exceptions\ServerErrorException;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\User\FriendService;
use Illuminate\Http\JsonResponse;

class FriendController extends Controller
{
    protected FriendService $friendService;

    public function __construct(FriendService $friendService)
    {
        $this->friendService = $friendService;
    }

    /**
     * @throws ServerErrorException
     */
    public function index(User $user): JsonResponse
    {
        $friends = $user->friends()->get();

        return response()->json([
            'status' => true,
            'friends' => UserResource::collection($friends),
        ]);
    }

    public function store(User $friend): JsonResponse
    {
        return $this->friendService->addFriend(\Auth::user(), $friend);

    }

    public function destroy(User $friend): JsonResponse
    {
        return $this->friendService->removeFriend(\Auth::user(), $friend);

    }
}
