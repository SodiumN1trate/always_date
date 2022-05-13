<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class AuthController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->stateless()->redirect();
    }

    public function handleProviderCallback()
    {
        $driverUser = Socialite::driver('facebook')->stateless()->user();
        $user = User::whereEmail($driverUser->email)->first();
        if(!$user) {
            $user = User::create([
                "name"=>$driverUser->name,
                "email"=>$driverUser->email,
                "provider_id"=>$driverUser->id
            ]);
        }

        $userToken = $user->createToken('login')->accessToken;
        return response()->json(['user'=>$user, 'access_token'=>$userToken], 200);
    }
}
