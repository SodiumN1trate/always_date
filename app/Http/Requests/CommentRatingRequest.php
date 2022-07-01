<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Comment rating request",
 *      description="",
 *      type="object",
 *      required={
 *          "life_school_comment_id",
 *          "rating",
 *      }
 * )
 */
class CommentRatingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @OA\Property(format="integer", description="Dzīves skolas raksta komentāra id", property="life_school_comment_id"),
     * @OA\Property(format="boolean", description="Vērtējums komentāram Like - true, Dislike - flalse", property="rating"),
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'life_school_comment_id' => 'required',
            'rating' => 'required',
        ];
    }
}
