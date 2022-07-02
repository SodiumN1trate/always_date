<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    public function redirectToProvider()
    {
        $url = Socialite::driver('facebook')->stateless()->redirect()->getTargetUrl();

        return response()->json([
            'data' => [
                'redirect_url' => $url,
            ]
        ]);
    }

    public function handleProviderCallback()
    {
        $driverUser = Socialite::driver('facebook')->stateless()->user();
        $user = User::whereEmail($driverUser->email)->first();

        if(!$user) {
            $user = User::create([
                'avatar' => $driverUser->avatar,
                'name' => $driverUser->name,
                'email' => $driverUser->email,
                'provider_id' => $driverUser->id,
            ]);
        }

        $userToken = $user->createToken('login')->accessToken;
        return response()->json([
            'message' => [
                'type' => 'success',
                'data' => 'Jūs veiksmīgi autentificējāties!',
            ],
            'data' => new UserResource($user),
            'access_token' => $userToken,
        ]);
    }

    /**
     * @OA\Get(
     *      path="/logout",
     *      operationId="userLogout",
     *      tags={"Auth"},
     *      summary="Lietotaja izrakstīšanās",
     *      description="Lietotaja izrakstīšanās no konta",
     *      security={{ "bearer": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Veiksmīga izrakstīšanās.",
     *          @OA\JsonContent()
     *      )
     *)
     */
    public function logout() {
        auth()->user()->token()->revoke();

        return response()->json([
           'message' => [
               'type' => 'success',
               'data' => 'Veiksmīga izrakstīšanās.'
           ]
        ]);
    }
}
