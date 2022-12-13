<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MatchLogResource;
use App\Http\Resources\UserResource;
use App\Models\MatchLog;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use mysql_xdevapi\Exception;

class MatchLogController extends Controller {
    /**
     * @OA\Get(
     *      path="/match",
     *      operationId="getMatchLog",
     *      tags={"Match log"},
     *      summary="Atgriež sakritības ierakstus",
     *      description="Atgriež visus sakritības ierakstus",
     *      security={{ "bearer": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/MatchLogResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unauthenticated",
     *      )
     *)
     */
    public function index() {
        return MatchLogResource::collection(MatchLog::all());
    }

    /**
     * @OA\Post(
     *      path="/match",
     *      operationId="postMatchLog",
     *      tags={"Match log"},
     *      summary="Izveido vai atjauno jaunu sakritības ierakstu",
     *      description="Ja nepastāv datubāzē ieraksts, tad tiek izveidots jauns, bet ja ieraksts pastāv, tad to ierakstu modificēs",
     *      security={{ "bearer": {} }},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type="object",
     *                  required = {"user_2", "user_1_rating"},
     *                  @OA\Property(format="integer", description="Lietotājs kuram tiks likts vērtējums", property="user_2"),
     *                  @OA\Property(format="boolean", description="Vērtējums true vai false", property="user_1_rating"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/MatchLogResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Nevar novērtēt pats sevi.",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Novērtēts ar nezināmu vērtējumu.",
     *      )
     *)
     */
    public function store(Request $request) {
        $validated = $request->validate([
            'user_2' => 'required',
            'user_1_rating' => 'required'
        ]);
        $validated['user_1'] = auth()->user()->id;


        if ($validated['user_2'] == $validated['user_1']) {
            return response()->json([
                'error' => [
                    'data' => 'Nevar novērtēt pats sevi.'
                ]
            ], 400);
        } elseif (!is_bool($validated['user_1_rating'])) {
            return response()->json([
                'error' => [
                    'data' => 'Novērtēts ar nezināmu vērtējumu.'
                ]
            ], 400);
        }

        $match = MatchLog::where('user_1', $validated['user_1'])
            ->where('user_2', $validated['user_2'])
            ->orWhere('user_1', $validated['user_2'])
            ->where('user_2', $validated['user_1'])->first();

        if ($match) {
            if ($validated['user_1'] == $match['user_1']) {
                $match->update([
                    'user_1' => $validated['user_1'],
                    'user_2' => $validated['user_2'],
                    'user_1_rating' => $validated['user_1_rating'],
                    ]);
            } elseif ($validated['user_1'] == $match['user_2']) {
                $match->update([
                    'user_1' => $validated['user_2'],
                    'user_2' => $validated['user_1'],
                    'user_2_rating' => $validated['user_1_rating'],
                    ]);
            }

            if ($match['user_1_rating'] && $match['user_2_rating']) {
                $match->update(['is_match' => true]);
            } else {
                $match->update(['is_match' => false]);
            }
        } else {
            $match = MatchLog::create($validated);
        }
        return new MatchLogResource($match);
    }

    /**
     * @OA\Get(
     *      path="/match/{id}",
     *      operationId="getMatchLogById",
     *      tags={"Match log"},
     *      summary="Iegūst sakritības ierakstu",
     *      description="Iegūst konkrētu sakritības ierakstu pēc id",
     *      security={{ "bearer": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="MatchLog id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/MatchLogResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unauthenticated.",
     *      ),
     * )
     */
    public function show(MatchLog $match) {
        return new MatchLogResource($match);
    }

    /**
     * @OA\Delete(
     *      path="/match/{id}",
     *      operationId="deleteMatchLog",
     *      tags={"Match log"},
     *      summary="Izdzēš sakritības ierakstu",
     *      description="Izdzēš sakritības ierakstu pēc id",
     *      security={{ "bearer": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="MatchLog id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/MatchLogResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unauthenticated",
     *      ),
     * )
     */
    public function destroy(MatchLog $match) {
        $match->delete();
        return new MatchLogResource($match);
    }

    public function isMatch() {
        $matchedUsers = MatchLog::where('user_1', auth()->user()->id)
            ->where('is_match', true)
            ->orWhere('user_2', auth()->user()->id)
            ->where('is_match', true)
            ->paginate(20)
            ->through(function ($match) {
                if ($match['user_1'] === auth()->user()->id ) {
                    return User::find($match['user_2']);
                } else {
                    return User::find($match['user_1']);
                }
            });
        return UserResource::collection($matchedUsers);
    }
    // Atgriež lietotājus kam autorizētais lietotājs uzlicis sirsniņu
    public function ratedMatchUser() {
        $users = MatchLog::where('user_1', auth()->user()->id)
            ->where('user_1_rating', true)
            ->orWhere('user_2', auth()->user()->id)
            ->where('user_2_rating', true)
            ->get()
            ->map(function ($match) {
                if ($match['user_1'] === auth()->user()->id) {
                    return User::find($match['user_2']);
                } elseif ($match['user_2'] === auth()->user()->id) {
                    return User::find($match['user_1']);
                }
            });
        return UserResource::collection($users)->paginate(20);
    }

    // Atgriež lietotājus kas autorizētajam lietotājam uzlikuši sirsniņu
    public function userMatchRated() {
        $users = MatchLog::where('user_1', auth()->user()->id)
            ->where('user_2_rating', true)
            ->orWhere('user_2', auth()->user()->id)
            ->where('user_1_rating', true)
            ->get()
            ->map(function ($match) {
                if ($match['user_1'] === auth()->user()->id) {
                    return User::find($match['user_2']);
                } elseif ($match['user_2'] === auth()->user()->id) {
                    return User::find($match['user_1']);
                }
            });
        return UserResource::collection($users)->paginate(20);
    }

    public function randomUser(Request $request) {
        try {
            while(true) {
                $user = User::filter($request->all())->inRandomOrder()
                    ->where('id', '!=', auth()->user()->id)
                    ->first();

                $match = MatchLog::where('user_1', $user->id)
                    ->where('user_2', auth()->user()->id)
                    ->orWhere('user_1', auth()->user()->id)
                    ->where('user_2', $user->id)->first();


                if (   (isset($match) && ($match->user_1 == auth()->user()->id && $match->user_1_rating == null)
                        || isset($match) && ($match->user_2 == auth()->user()->id && $match->user_2_rating == null))
                    || !isset($match)
                ) {
                    return new UserResource($user);
                }
            }
        } catch (Exception $e) {
            return response()->json([
                 'message' => $e,
                ], 400);
        }
    }

}
