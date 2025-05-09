<?php

namespace App\Services;

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
            'timezone' => $data['timezone'],
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
        if (isset($data['photo']) && $data['photo'] != null) {
            if ($user->photo) {
                DeletePublicPhoto($user->photo);
            }
            $data['photo'] = NewPublicPhoto($data['photo'], 'profiles');
        }

        $user->update([
            'name' => $data['name'],
            'photo' => $data['photo'] ?? $user->photo,
        ]);

        return $user;
    }
}
