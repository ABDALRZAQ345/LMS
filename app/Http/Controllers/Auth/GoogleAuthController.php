<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\ServerErrorException;
use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Responses\LogedInResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class GoogleAuthController extends BaseController
{
    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function handleGoogleUser($idToken): JsonResponse
    {

        DB::beginTransaction();
        $googleUser = Http::get("https://oauth2.googleapis.com/tokeninfo?id_token={$idToken}")->json();

        if (! isset($googleUser['email'])) {
            return response()->json(['error' => 'Invalid Google ID token'], 401);
        }

        $user = User::firstOrCreate([
            'email' => $googleUser['email'],
        ], [
            'name' => $googleUser['name'],
            'google_id' => $googleUser['sub'],
            'password' => Hash::make(str()->random(24)),
        ]);

        DB::commit();

        return LogedInResponse::response($user);

    }

    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function auth(Request $request): JsonResponse
    {
        $idToken = $request->input('id_token');

        if (! $idToken) {
            return response()->json(['error' => 'No token provided'], 400);
        }

        return $this->handleGoogleUser($idToken);

    }
}
