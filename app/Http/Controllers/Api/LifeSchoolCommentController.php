<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRatingRequest;
use App\Http\Requests\LifeSchoolCommentRequest;
use App\Http\Resources\CommentRatingResource;
use App\Http\Resources\LifeSchoolCommentResource;
use App\Models\CommentRating;
use App\Models\LifeSchoolComment;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class LifeSchoolCommentController extends Controller
{
    /**
     * @OA\Get(
     *      path="/life_school_comment",
     *      operationId="getLifeSchoolComment",
     *      tags={"Life school comment"},
     *      summary="Iegūst visus dzīves skolas komentārus",
     *      description="Iegūst visus dzīves skolas komentārus",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unauthenticated",
     *      )
     *)
     */
    public function index()
    {
        return LifeSchoolCommentResource::collection(LifeSchoolComment::all());
    }

    /**
     * @OA\Post(
     *      path="/life_school_comment",
     *      operationId="postLifeSchoolComment",
     *      tags={"Life school comment"},
     *      summary="Tiek uzrakstīts un saglabāts komentārs dzīves skolas rakstam",
     *      description="Ievada komentāru zem kādas dzīves skolas raksta, komentārs glabājas un, glabā visus nepiciešamos datus, lai būtu saistīts ar konkrētu komentāru.",
     *      @OA\Parameter(
     *          name="owner_id",
     *          description="Lietotāja id, kurš izveidoja komentāru",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *           name="description",
     *           description="Pats komentārs",
     *           required=true,
     *           in="path",
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *      @OA\Parameter(
     *           name="article_id",
     *           description="Dzīves skolas raksta id",
     *           required=true,
     *           in="path",
     *           @OA\Schema(
     *               type="integer"
     *           )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unauthenticated.",
     *      )
     *)
     */
    public function store(LifeSchoolCommentRequest $request)
    {
        $lifeSchoolComment = LifeSchoolComment::create($request->validated());
        return new LifeSchoolCommentResource($lifeSchoolComment);
    }

    /**
     * @OA\Get(
     *      path="/life_school_comment/{id}",
     *      operationId="getLifeSchoolCommentById",
     *      tags={"Life school comment"},
     *      summary="Iegūst konkrētu komentāru",
     *      description="Iegūst konkrētu komentāru, ar tā komentāra palīdzību",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unauthenticated",
     *      ),
     * )
     */
    public function show(LifeSchoolComment $lifeSchoolComment)
    {
        return new LifeSchoolCommentResource($lifeSchoolComment);
    }

    /**
     * @OA\Put(
     *      path="/life_school_comment/{id}",
     *      operationId="updateLifeSchoolComment",
     *      tags={"Life school comment"},
     *      summary="Atjauno vai reģidē komentāru",
     *      description="Atjauno vai reģidē komentāru pēc id",
     *      @OA\Parameter(
     *          name="owner_id",
     *          description="Lietotāja id, kurš izveidoja komentāru",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="description",
     *          description="Pats komentārs",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="article_id",
     *          description="Dzīves skolas raksta id",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unauthenticated",
     *      ),
     *)
     */
    public function update(LifeSchoolCommentRequest $request, LifeSchoolComment $lifeSchoolComment)
    {
        $lifeSchoolComment->update($request->validated());
        return new LifeSchoolCommentResource($lifeSchoolComment);
    }

    /**
     * @OA\Delete(
     *      path="/life_school_comment/{id}",
     *     operationId="DeleteLifeSchoolComment",
     *     tags={"Life school comment"},
     *     summary="Izdzēš komentāru",
     *     description="Dzēš komentāru pēc id",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unauthenticated",
     *      ),
     * )
     */
    public function destroy(LifeSchoolComment $lifeSchoolComment)
    {
        $lifeSchoolComment->delete();
        return new LifeSchoolCommentResource($lifeSchoolComment);
    }

    /**
     * @OA\Post(
     *      path="/life_school_comment/rate",
     *      operationId="rateLifeSchoolComment",
     *      tags={"Life school comment"},
     *      summary="Komentāru vērtēšana",
     *      description="Lietotās spēj novērtēt komentāru ar like vai dislike. Viss tiek validēts tā, lai lietotājs nevar novērtēt komentru ar nezināmu vērtējumu vai ar 2 vērtējumiem vienlaicīgi",
     *      @OA\Parameter(
     *          name="life_school_comment_id",
     *          description="Komentāra id kurš tiks vērtēts",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="rating",
     *          description="Vērtēšana true - like, false - dislike",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="boolean"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Nevar novērtēt komentāru ar nezinānu vērtējumu.",
     *      )
     *)
     */
    public function rate(Request $request) {
        $rate = $request->validate([
            'life_school_comment_id' => 'required',
            'rating' => 'required',
        ]);

        $ratedComment = CommentRating::where('life_school_comment_id', $rate['life_school_comment_id'])
            ->where('rater_id', auth()->user()->id)->first();

        if ($ratedComment) {
            if ($rate['rating'] <= -1 || $rate['rating'] >= 2) {
                return response()->json([
                    'error' => [
                        'data' => 'Nevar novērtēt komentāru ar nezinānu vērtējumu.',
                    ]
                ], 400);
            } elseif ($ratedComment['rating'] == $rate['rating']) {
                $ratedComment['rating'] = -1;
            } else {
                $ratedComment['rating'] = $rate['rating'];
            }

            $ratedComment->update(array($ratedComment));
        } else {
            $rate['rater_id'] = auth()->user()->id;
            CommentRating::create($rate);
        }

       $LifeSchoolComment = LifeSchoolComment::where('id', $rate['life_school_comment_id'])->first();
        $LifeSchoolLikes = CommentRating::where('life_school_comment_id', $rate['life_school_comment_id'])
            ->where('rating', 1)->get();
        $LifeSchoolDislikes = CommentRating::where('life_school_comment_id', $rate['life_school_comment_id'])
            ->where('rating', 0)->get();

        $LifeSchoolComment->update([
            'likes' => count($LifeSchoolLikes),
            'dislikes' => count($LifeSchoolDislikes),
        ]);
        return new LifeSchoolCommentResource($LifeSchoolComment);
    }
}
