<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Life school comment request",
 *      description="",
 *      type="object",
 *      required={
 *          "owner_id",
 *          "description",
 *          "article_id",
 *      }
 * )
 */
class LifeSchoolCommentRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * @OA\Property(format="integer", description="Dzīves skolas raksta komentāta autora id", property="owner_id"),
     * @OA\Property(format="string", description="Dzīves skolas raksta komentāra apraksts", property="description"),
     * @OA\Property(format="integer", description="Dzīves skolas raksta id", property="article_id"),
     *
     * @return array<string, mixed>
     */
    public function rules() {
        return [
            'owner_id' => 'required',
            'description' => 'required',
            'article_id' => 'required',
        ];
    }

}
