<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MatchLogResource;
use App\Http\Resources\UserResource;
use App\Models\MatchLog;
use App\Models\User;
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
     *      description="Izveido vai atjauno jaunu sakritības ierakstu",
     *      security={{ "bearer": {} }},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type="object",
     *                  required = {"user_2"},
     *                  @OA\Property(format="integer", description="Lietotājs kuram tiks likts vērtējums", property="user_2"),
     *                  @OA\Property(format="boolean", description="Vērtējums 1 - true, 0 - false", property="mark"),
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
     *          description="Vienu reizi var likt vērtējumu.",
     *      )
     *)
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

        if ($matchLog['user_2'] == auth()->user()->id){
            return response()->json([
                'error' =>[
                    'data' => 'Nevar novērtēt pats sevi.',
                ]
            ]);
        } elseif($isMatchExist1) {
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

    public function nextRandomUser($skippedUserId = null){
        $randomMatchingUser = User::inRandomOrder()->where('id', '<>', $skippedUserId)
            ->where('id', '<>', auth()->user()->id)
            ->where('gender', '<>', auth()->user()->gender)->first();

        return new UserResource($randomMatchingUser);
    }
}
