<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RatingLog;
use App\Models\User;

class UserController extends Controller
{
    public function user()
    {
        return auth()->user();
    }

    public function rate(Request $request)
    {
        $validated = $request->validate([
            "user_id" => 'required',
            "rater_id" => 'required',
            "rating" => 'required'
        ]);
//        var_dump(RatingLog::where("user_id", $validated["user_id"])->where("rater_id", $validated["rater_id"])->get());
        $raterRates = RatingLog::where("user_id", $validated["user_id"])->where("rater_id", $validated["rater_id"])->get();

        if (count($raterRates) > 1) {
            return response()->json(['message' => 'Jūs jau esat novērtējuši.'], 400);
        } else {
            RatingLog::create($validated);
            $user = User::find($validated['user_id']);
            $userRates = RatingLog::where('user_id', $user->id)->select('rating')->get();
            $ratingSum = 0;
            foreach ($userRates as $value) {
                $ratingSum += $value['rating'];
            }
            $user->update(['rating' => round($ratingSum / count($userRates), 2)]);
            return response()->json(["data" => $user]);
        }
    }
}
