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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return LifeSchoolCommentResource::collection(LifeSchoolComment::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LifeSchoolCommentRequest $request)
    {
        $lifeSchoolComment = LifeSchoolComment::create($request->validated());
        return new LifeSchoolCommentResource($lifeSchoolComment);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(LifeSchoolComment $lifeSchoolComment)
    {
        return new LifeSchoolCommentResource($lifeSchoolComment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LifeSchoolCommentRequest $request, LifeSchoolComment $lifeSchoolComment)
    {
        $lifeSchoolComment->update($request->validated());
        return new LifeSchoolCommentResource($lifeSchoolComment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(LifeSchoolComment $lifeSchoolComment)
    {
        $lifeSchoolComment->delete();
        return new LifeSchoolCommentResource($lifeSchoolComment);
    }

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
                        'data' => 'Nevar novērtēt komentaru ar nezinānu vērtējumu.',
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
