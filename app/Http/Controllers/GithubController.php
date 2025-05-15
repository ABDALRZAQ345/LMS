<?php

namespace App\Http\Controllers;

use App\Responses\LogedInResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;
class GithubController extends Controller
{
    public function callback()
    {

            $githubUser = Socialite::driver('github')->stateless()->user();


            $user = User::firstOrCreate(
                ['github_id' => $githubUser->id],
                [
                    'name' => $githubUser->name ?? $githubUser->nickname,
                    'email' => $githubUser->email,
                    'github_token' => $githubUser->token,
                    'password' => Hash::make(Str::random(16)),
                ]
            );
            return LogedInResponse::response($user);
    }
}
