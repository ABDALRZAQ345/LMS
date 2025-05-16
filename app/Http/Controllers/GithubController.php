<?php

namespace App\Http\Controllers;

use App\Exceptions\ServerErrorException;
use App\Models\User;
use App\Responses\LogedInResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

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

    /**
     * @throws ServerErrorException
     */
    public function redirectToGithub()
    {
        try {
            return Socialite::driver('github')->stateless()->redirect();
        } catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }
}
