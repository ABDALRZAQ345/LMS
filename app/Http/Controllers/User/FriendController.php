<?php

namespace App\Http\Controllers\User;

use App\Exceptions\ServerErrorException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Users\UserResource;
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
        $friends = $user->friends()->paginate(20);

        return response()->json([
            'status' => true,
            'friends' => UserResource::collection($friends),
            'meta' => getMeta($friends)
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
