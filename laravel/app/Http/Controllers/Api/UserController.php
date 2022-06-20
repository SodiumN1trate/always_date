<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Models\RatingLog;
use App\Models\User;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function user()
    {
        return new UserResource(auth()->user());
    }

    public function index(Request $request)
    {
        $users = User::filter($request->all())->paginate(10);
        return UserResource::collection($users);
    }

    public function update(UserRequest $request)
    {
        auth()->user()->update($request->validated());
        return new UserResource(auth()->user());
    }

    public function rate(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required',
            'rating' => 'required',
        ]);
        $validated['rater_id'] = auth()->user()->id;

        $raterRates = RatingLog::where('user_id', $validated['user_id'])
            ->where('rater_id', auth()->user()->id)->get();

        if (count($raterRates) > 0) {
            return response()->json([
                'error' => [
                    'data' => 'Jūs jau esat novērtējuši.',
                ]
            ], 400);
        } elseif ($validated['user_id'] == auth()->user()->id) {
            return response()->json([
                'error' => [
                    'data' => 'Nevar novērtēt pats sevi.',
                ]
            ], 400);
        } else {
            RatingLog::create($validated);
            $user = User::find($validated['user_id']);
            $userRates = RatingLog::where('user_id', $user->id)->select('rating')->get();
            $ratingSum = 0;
            foreach ($userRates as $value) {
                $ratingSum += $value['rating'];
            }
            $user->update(['rating' => round($ratingSum / count($userRates), 2)]);
            return new UserResource($user);
        }
    }

    public function ratedUser() {
        $ratings = RatingLog::where('user_id', auth()->user()->id)->get();
        $users = array();
        $uniqueUser = array();

        foreach ($ratings as $user) {
            if (!in_array($user['rater_id'], $uniqueUser)) {
                $users[] = User::where('id', $user['rater_id'])->first();
                $uniqueUser[] = $user['rater_id'];
            }
        }
        return UserResource::collection($users);
    }

    public function userRated() {
        $ratings = RatingLog::where('rater_id', auth()->user()->id)->get();
        $users = array();
        $uniqueUser = array();

        foreach ($ratings as $user) {
            if (!in_array($user['user_id'], $uniqueUser)) {
                $users[] = User::where('id', $user['user_id'])->first();
                $uniqueUser[] = $user['user_id'];
            }
        }
        return UserResource::collection($users);
    }
}
