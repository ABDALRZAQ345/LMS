<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\ServerErrorException;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Responses\LogedInResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function handleGithubUser($token): JsonResponse
    {
        DB::beginTransaction();
        try {
            $githubUser = Socialite::driver('github')->stateless()->userFromToken($token);

            $user = User::firstOrCreate(
                [
                    'email' => $githubUser->getEmail(),
                ],
                [
                    'name'      => $githubUser->getName() ?? $githubUser->getNickname(),
                   // 'github_id' => $githubUser->getId(),
                    'password'  => Hash::make(Str::random(24)),
                ]
            );

            DB::commit();

            return LogedInResponse::response($user);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new ServerErrorException($exception->getMessage());
        }
    }

    public function auth(Request $request): JsonResponse
    {
        $token = $request->input('access_token');

        if (! $token) {
            return response()->json(['error' => 'No token provided'], 400);
        }

        return $this->handleGithubUser($token);
    }
}
