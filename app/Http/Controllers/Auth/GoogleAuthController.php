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
use Google\Client as GoogleClient;
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

            $client = new GoogleClient(['client_id' => config('services.google.client_id')]);

            $payload = $client->verifyIdToken($idToken);
            if (!$payload) {

                return response()->json(['error' => 'Invalid Google ID token'], 401);
            }

            $user = User::firstOrCreate([
                'email' =>$payload['email'],
            ], [
                'name' => $payload['name'],
                'google_id' => $payload['sub'],
                'password' => Hash::make(str()->random(24)),
            ]);

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
