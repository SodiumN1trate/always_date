<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MatchLogResource;
use App\Models\MatchLog;
use App\Models\User;
use Illuminate\Http\Request;

class MatchLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MatchLogResource::collection(MatchLog::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $matchLog = $request->validate([
            'user_2' => 'required',
            'mark' => '',
        ]);

        $isMatchExist1 = MatchLog::where('user_1', auth()->user()->id)
            ->where('user_2', $matchLog['user_2'])->first();

        $isMatchExist2 = MatchLog::where('user_2', auth()->user()->id)
            ->where('user_1', $matchLog['user_2'])->first();

        if($isMatchExist1) {
            return response()->json([
                'error' => [
                    'data' => 'Vienu reizi var likt vērtējumu.',
                ]
            ], 400);
        } elseif ($isMatchExist2) {
            if($matchLog['mark'] >= 1 && $isMatchExist2->is_match === 1) {
                $isMatchExist2->is_match = 1;
            } else {
                $isMatchExist2->is_match = 0;
            }
            $isMatchExist2->update(array($isMatchExist2));
            return new MatchLogResource($isMatchExist2);
        } else {
            $matchLog['user_1'] = auth()->user()->id;
            $matchLog['is_match'] = ($matchLog['mark'] >= 1) ? 1 : 0;
            unset($matchLog['mark']);
            $match = MatchLog::create($matchLog);
            return new MatchLogResource($match);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(MatchLog $matchLog)
    {
        return new MatchLogResource($matchLog);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(MatchLog $matchLog)
    {
        $matchLog->delete();
        return new MatchLogResource($matchLog);
    }
}
