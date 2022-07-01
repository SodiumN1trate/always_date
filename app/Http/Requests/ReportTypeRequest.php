<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Report type request",
 *      description="",
 *      type="object",
 *      required={
 *          "title",
 *      }
 * )
 */
class ReportTypeRequest extends FormRequest
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
     * @OA\Property(format="string", description="Sūdzības veids/īss skaidrojums", property="title"),
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required',
        ];
    }
}
