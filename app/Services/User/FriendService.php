<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Http\JsonResponse;

class FriendService
{
    public function addFriend(User $user, User $friend): JsonResponse
    {
        if ($user->id == $friend->id) {
            return response()->json([
                'status' => false,
                'message' => 'You cannot add yourself as a friend.',
            ]);
        }

        if ($friend->role == 'teacher') {
            return response()->json([
                'status' => false,
                'message' => 'common nigga you think that teachers are your friends ? have some respect please do not  add teachers as friends',
            ]);
        }

        if ($user->friends()->where('friend_id', $friend->id)->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'This user is already your friend.',
            ]);
        }

        $user->friends()->attach($friend->id);

        return response()->json([
            'status' => true,
            'message' => 'Friend added successfully.',
        ]);
    }

    public function removeFriend(User $user, User $friend): JsonResponse
    {
        $data = [];
        $status_code = 200;
        if ($user->id == $friend->id) {
            $data = [
                'status' => false,
                'message' => 'common man you cant delete yourself',
            ];
            $status_code = 400;
        } elseif (! $user->friends()->where('friend_id', $friend->id)->exists()) {
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
