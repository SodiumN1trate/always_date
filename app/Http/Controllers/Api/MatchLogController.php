<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MatchLogResource;
use App\Http\Resources\UserResource;
use App\Models\MatchLog;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;

class MatchLogController extends Controller
{
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
    public function index()
    {
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

        $match = MatchLog::where('user_1', $validated['user_1'])
            ->where('user_2', $validated['user_2'])
            ->orWhere('user_1', $validated['user_2'])
            ->where('user_2', $validated['user_1'])->first();

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
        } elseif ($match) {
            if ($match['user_1'] != $validated['user_1']) {
                $match->update(array(
                    'user_2' => $validated['user_2'],
                    'user_1' => $validated['user_1'],
                    'user_2_rating' => $match['user_1_rating'],
                    'user_1_rating' => $validated['user_1_rating'],
                ));
            }
            $match->update(array(
                'user_1_rating' => $validated['user_1_rating']
            ));

            $match->update(array('is_match' => $match['user_2_rating'] == 1 && $match['user_1_rating'] == 1));
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
    public function show(MatchLog $match)
    {
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
    public function destroy(MatchLog $match)
    {
        $match->delete();
        return new MatchLogResource($match);
    }

    public function randomUser($skippedUserId = null){
        $randomMatchingUser = User::inRandomOrder()->where('id', '!=', $skippedUserId)
            ->where('id', '!=', auth()->user()->id)->first();

        return new UserResource($randomMatchingUser);
    }
}
