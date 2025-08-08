<?php

namespace App\Services\User;

use App\Http\Resources\Users\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService
{
    public static function createUser(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'password' => Hash::make($data['password']),
            'fcm_token' => $data['fcm_token'] ?? null,
            'email' => $data['email'],
            'image' => isset($data['photo']) ? NewPublicPhoto($data['image'], 'profiles') : null,
            'gitHub_account' => $data['gitHub_account'] ?? null,
            'bio' => $data['bio'] ?? null,
            'role' => $data['role'] ?? 'student',
            'email_verified' => $data['email_verified'] ?? false,
        ]);
    }

    public static function updatePassword($user, $newPassword): void
    {
        $user->update([
            'password' => Hash::make($newPassword),
        ]);
    }

    public static function updateUser($data): User
    {
        $user = Auth::user();
        if (isset($data['image']) && $data['image'] != null) {

            if ($user->image)  DeletePublicPhoto($user->image);
            $data['image'] = NewPublicPhoto($data['image']);
        }

        $user->update([
            'name' => $data['name'],
            'image' => $data['image'] ?? null,
            'bio' => $data['bio'] ?? null,
            'gitHub_account' => $data['gitHub_account'] ?? null,
            'age' => $data['age'] ?? null
        ]);

        return $user;
    }

    public static function deleteUnVerifiedUser($email): true
    {
        User::where('email', $email)
            ->where('email_verified', false)
            ->delete();

        return true;

    }

    public function GetUsers($friends = 0, $role = 'student', $search = '', $orderBy = 'points', $direction = 'desc',$items=30): \Illuminate\Pagination\LengthAwarePaginator
    {

        $user = auth('api')->user();
        if ($user && $friends) {
            $users = $user->friends();
        } else {
            $users = User::query();
        }

        if($role != 'all')
         $users->where('role', $role);

       return $users->orderBy($orderBy, $direction)
        ->where('name', 'like', '%'.$search.'%')
        ->paginate($items);




    }

    public function CreateTeacher($data): JsonResponse
    {

        $data['role'] = 'teacher';
        $data['email_verified'] = true;
        UserService::createUser($data);

        return response()->json([
            'status' => true,
            'message' => 'Teacher created successfully',
            'data'=> [
                'email'=> $data['email'],
                'password'=>$data['password']
            ]
        ]);

    }

    public function UpdateFcmToken($token): void
    {
        $user=Auth::user();
        if($user){
            $user->update([
                'fcm_token' => $token
            ]);
        }
    }
}
