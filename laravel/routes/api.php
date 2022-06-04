<?php

use App\Http\Controllers\api\LifeSchoolController;
use App\Http\Controllers\Api\MatchLogController;
use App\Http\Controllers\Api\ReportTypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
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


Route::get('/authorize', [AuthController::class, 'redirectToProvider']);
Route::get('/callback-url', [AuthController::class, 'handleProviderCallback']);

Route::group(['middleware' => ['auth:api']], function() {
    Route::get('/user', [UserController::class, 'user']);
    Route::post('/user/rate', [UserController::class, 'rate']);
    Route::apiResource('/life_school', LifeSchoolController::class);
    Route::apiResource('/match', MatchLogController::class);
    Route::apiResource('/report_type', ReportTypeController::class);
});
