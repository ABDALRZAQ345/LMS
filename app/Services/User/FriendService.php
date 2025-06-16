<?php

namespace App\Services\User;

use App\Models\User;
use App\Services\AchievementsService;
use Illuminate\Http\JsonResponse;

class FriendService
{
    protected AchievementsService  $achievementsService;
    public function __construct(AchievementsService $achievementsService)
    {
        $this->achievementsService = $achievementsService;
    }
    public function addFriend(User $user, User $friend): JsonResponse
    {

        if ($user->id == $friend->id) {
          $data=[
                'status' => false,
                'message' => 'You cannot add yourself as a friend.',
            ];
          $status_code=400;
        }
        else if ($friend->role == 'teacher') {
            $data=[
                'status' => false,
                'message' => 'common nigga you think that teachers are your friends ? have some respect please do not  add teachers as friends',
            ];
            $status_code=400;
        }
        else if ($user->HasFriend($friend)) {
            $data=[
                'status' => false,
                'message' => 'This user is already your friend.',
            ];
            $status_code=400;
        }
        else {
            $user->friends()->attach($friend->id);
           $data=[
                'status' => true,
                'message' => 'Friend added successfully.',
            ];
           $status_code=200;
        }
        return response()->json($data, $status_code);

    }

    public function removeFriend(User $user, User $friend): JsonResponse
    {

        $status_code = 200;
        if ($user->id == $friend->id) {
            $data = [
                'status' => false,
                'message' => 'common man you cant delete yourself',
            ];
            $status_code = 400;
        } elseif (! $user->HasFriend($friend)) {
            $data = [
                'status' => false,
                'message' => 'the user is not a friend',
            ];
            $status_code = 400;
        } else {
            $user->friends()->detach($friend);
            $data = [
                'status' => true,
                'message' => 'Friend removed successfully',
            ];
        }

        return response()->json($data, $status_code);
    }
}
