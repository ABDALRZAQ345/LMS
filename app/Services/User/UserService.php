<?php

namespace App\Services\User;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            if ($user->image) {
                DeletePublicPhoto($user->image);
            }
            $data['image'] = NewPublicPhoto($data['image']);
        }

        $user->update([
            'name' => $data['name'],
            'image' => $data['image'] ?? null,
            'bio' => $data['bio'] ?? null,
            'gitHub_account' => $data['gitHub_account'] ?? null,
        ]);

        return $user;
    }

    public static function deleteUnVerifiedUser($email): true
    {
        $user = User::where('email', $email)
            ->where('email_verified', false)
            ->delete();

        return true;

    }

    public function GetUsers($friends = 0, $role = 'student', $search = '', $orderBy = 'points', $direction = 'desc')
    {

        $user = Auth::user();

        if ($friends) {
            $users = $user->friends()->where('role', $role)->orderBy($orderBy, $direction)
                ->where('name', 'like', '%'.$search.'%')
                ->paginate(20);
        } else {
            $users = User::where('role', $role)->where('name', 'like', '%'.$search.'%')->orderBy($orderBy, $direction)->paginate(20);
        }

        return UserResource::collection($users);

    }
}
