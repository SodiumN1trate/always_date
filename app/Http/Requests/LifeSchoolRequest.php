<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


/**
 * @OA\Schema(
 *      title="Life school request",
 *      description="",
 *      type="object",
 *      required={
 *          "title",
 *          "gender",
 *          "description",
 *      }
 * )
 */
class LifeSchoolRequest extends FormRequest
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
     * @OA\Property(format="string", description="Dzīves skolas virsraksts", property="title"),
     * @OA\Property(format="boolean", description="Dzimums priekš dzīves skolas raksta", property="gender"),
     * @OA\Property(format="string", description="Dzīves skolas raksta apraksts", property="description"),
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'gender' => 'required',
            'description' => 'required',
        ];
    }
}
