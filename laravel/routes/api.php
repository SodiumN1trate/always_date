<?php

use App\Http\Controllers\Api\LifeSchoolCommentController;
use App\Http\Controllers\api\LifeSchoolController;
use App\Http\Controllers\Api\MatchLogController;
use App\Http\Controllers\Api\ReportLogController;
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
    Route::get('/me', [UserController::class, 'user']);
    Route::put('/me', [UserController::class, 'update']);
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/user/rate', [UserController::class, 'rate']);
    Route::apiResource('/life_school', LifeSchoolController::class);
    Route::apiResource('/life_school_comment', LifeSchoolCommentController::class);
    Route::apiResource('/match', MatchLogController::class);
    Route::apiResource('/report_type', ReportTypeController::class);
    Route::apiResource('/report_log', ReportLogController::class);
    Route::post('/life_school_comment/rate', [LifeSchoolCommentController::class, 'rate']);
    Route::get('/logout', [AuthController::class, 'logout']);
});
