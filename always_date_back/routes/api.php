<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\API\SocialiteAuthController;
use App\Http\Controllers\API\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

// Socialite authorization (facebook)
Route::get('/authorize', [SocialiteAuthController::class, 'redirectToProvider']);
Route::get('/callback-url', [SocialiteAuthController::class, 'handleProviderCallBack']);

// User
Route::get('/user', [UserController::class, 'user'])->middleware('auth:api');
