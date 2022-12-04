<?php

use App\Http\Controllers\Api\ChatRoomController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\LifeSchoolCommentController;
use App\Http\Controllers\Api\LifeSchoolController;
use App\Http\Controllers\Api\MatchLogController;
use App\Http\Controllers\Api\ReportLogController;
use App\Http\Controllers\Api\ReportTypeController;
use GrahamCampbell\Throttle\Http\Middleware\ThrottleMiddleware;
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
    Route::resource('users', UserController::class);
    Route::post('/user/rate', [UserController::class, 'rate']);
    Route::get('/user_rated', [UserController::class, 'userRated']);
    Route::get('/rated_user', [UserController::class, 'ratedUser']);
    Route::get('/logout', [AuthController::class, 'logout']);

    Route::resource('life_school', LifeSchoolController::class);
    Route::resource('life_school_comment', LifeSchoolCommentController::class);
    Route::post('/life_school_comment/rate', [LifeSchoolCommentController::class, 'rate']);
    Route::group(['middleware' => ['throttle:20,1']], function () {
        Route::resource('match', MatchLogController::class);
    });

    Route::resource('match', MatchLogController::class);
    Route::get('/random_user', [MatchLogController::class, 'randomUser']);
    Route::get('/rated_match_user', [MatchLogController::class, 'ratedMatchUser']);
    Route::get('/user_match_rated', [MatchLogController::class, 'userMatchRated']);

    Route::resource('report_type', ReportTypeController::class);
    Route::resource('report_log', ReportLogController::class);

    Route::resource('chat_room', ChatRoomController::class);
    Route::post('/messages', [MessageController::class, 'message']);
    Route::post('/chat_room_messages', [MessageController::class, 'chatRoomMessages']);
});
