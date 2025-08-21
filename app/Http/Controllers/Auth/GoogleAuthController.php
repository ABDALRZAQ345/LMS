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
use Laravel\Socialite\Facades\Socialite;
use Str;

class GoogleAuthController extends BaseController
{

    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function handleGoogleUser($idToken): JsonResponse
    {


        DB::beginTransaction();
        try {

            $googleUser = Socialite::driver('google')->stateless()->userFromToken($idToken);
        //   \Log::channel('verification_code')->info($googleUser);

            $user = User::firstOrCreate(
                [
                    'email' => $googleUser->getEmail(),
                ],
                [
                    'name'      => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'password'  => Hash::make(Str::random(24)),
                ]
            );

            DB::commit();

            return LogedInResponse::response($user);
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
            throw new ServerErrorException($exception->getMessage());
        }
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
