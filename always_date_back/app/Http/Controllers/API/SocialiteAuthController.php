<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class SocialiteAuthController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->stateless()->redirect();
    }

    public function handleProviderCallBack()
    {
        $user =  Socialite::driver('facebook')->stateless()->user();

        // Check if token exists
        if(!$user->token) {
            dd("failed");
        }

        // Check if user is registered
        $appUser = User::whereEmail($user->email)->first();
        if(!$appUser) {
            $appUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'provider_user_id' => $user->id
            ]);
        }

        // Login
        $accessToken = $appUser->createToken('Login token')->accessToken;

        return response()->json(['accessToken' => $accessToken]);
    }
}
