<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRatingRequest;
use App\Http\Requests\LifeSchoolCommentRequest;
use App\Http\Resources\LifeSchoolCommentResource;
use App\Models\CommentRating;
use App\Models\LifeSchoolComment;
use App\Models\RatingLog;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class LifeSchoolCommentController extends Controller {
    /**
     * @OA\Get(
     *      path="/life_school_comment",
     *      operationId="getLifeSchoolComment",
     *      tags={"Life school comment"},
     *      summary="Iegūst visus dzīves skolas komentārus",
     *      description="Iegūst visus dzīves skolas komentārus",
     *      security={{ "bearer": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/LifeSchoolCommentResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unauthenticated",
     *      )
     *)
     */
    public function index(Request $request) {
        return LifeSchoolCommentResource::collection(LifeSchoolComment::where('article_id', $request->article_id)->get());
    }

    /**
     * @OA\Post(
     *      path="/life_school_comment",
     *      operationId="postLifeSchoolComment",
     *      tags={"Life school comment"},
     *      summary="Tiek uzrakstīts un saglabāts komentārs dzīves skolas rakstam",
     *      description="Ievada komentāru zem kādas dzīves skolas raksta, komentārs glabājas un, glabā visus nepiciešamos datus, lai būtu saistīts ar konkrētu komentāru.",
     *      security={{ "bearer": {} }},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type="object",
     *                  ref="#components/schemas/LifeSchoolCommentRequest",
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/LifeSchoolCommentResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unauthenticated.",
     *      )
     *)
     */
    public function store(LifeSchoolCommentRequest $request) {
        $validated = $request->validated();
        $validated['owner_id'] = auth()->user()->id;
        $validated['votes'] = 0;
        $lifeSchoolComment = LifeSchoolComment::create($validated);
        return new LifeSchoolCommentResource($lifeSchoolComment);
    }


    /**
     * @OA\Put(
     *      path="/life_school_comment/{id}",
     *      operationId="updateLifeSchoolComment",
     *      tags={"Life school comment"},
     *      summary="Atjauno vai reģidē komentāru",
     *      description="Atjauno vai reģidē komentāru pēc id",
     *      security={{ "bearer": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="LifeSchoolComment id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type="object",
     *                  ref="#components/schemas/LifeSchoolCommentRequest"
     *              )
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/LifeSchoolCommentResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unauthenticated",
     *      ),
     *)
     */
    public function update(LifeSchoolCommentRequest $request, LifeSchoolComment $lifeSchoolComment) {
        $lifeSchoolComment->update($request->validated());
        return new LifeSchoolCommentResource($lifeSchoolComment);
    }

    /**
     * @OA\Delete(
     *      path="/life_school_comment/{id}",
     *     operationId="deleteLifeSchoolComment",
     *     tags={"Life school comment"},
     *     summary="Izdzēš komentāru",
     *     description="Dzēš komentāru pēc id",
     *      security={{ "bearer": {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="LifeSchoolComment id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/LifeSchoolCommentResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Unauthenticated",
     *      ),
     * )
     */
    public function destroy(LifeSchoolComment $lifeSchoolComment) {
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
     *      security={{ "bearer": {} }},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  type="object",
     *                  ref="#components/schemas/CommentRatingRequest",
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/LifeSchoolCommentResource")
     *
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Nevar novērtēt komentāru ar nezinānu vērtējumu.",
     *      )
     *)
     */
    public function rate(CommentRatingRequest $request) {
        $rate = $request->validated();
        $rate['rater_id'] = auth()->user()->id;

        $ratedComment = CommentRating::where('life_school_comment_id', $rate['life_school_comment_id'])
            ->where('rater_id', auth()->user()->id)->first();

        if ($ratedComment) {
            if ($ratedComment->rating === $request['rating']) {
                $ratedComment->rating = null;
            } else {
                $ratedComment->rating = $request['rating'];
            }
            $ratedComment->save();
        } else {
            CommentRating::create($rate);
        }

        $lifeSchoolComment = LifeSchoolComment::where('id', $rate['life_school_comment_id'])->first();
        $lifeSchoolLikes = CommentRating::where('life_school_comment_id', $rate['life_school_comment_id'])
            ->where('rating', 1)->count();
        $lifeSchoolDislikes = CommentRating::where('life_school_comment_id', $rate['life_school_comment_id'])
            ->where('rating', 0)->count();

        $lifeSchoolComment->votes = $lifeSchoolLikes - $lifeSchoolDislikes;
        $lifeSchoolComment->save();
        return new LifeSchoolCommentResource($lifeSchoolComment);
    }
}
