<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="User request",
 *      description="",
 *      type="object",
 *      required={
 *          "name",
 *          "email",
 *          "provider_id",
 *      }
 * )
 */
class UserRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * @OA\Property(format="string", description="Lietotāja profila bilde", property="avatar"),
     * @OA\Property(format="string", description="Lietotāja pilnais vārds un uzvārds", property="name"),
     * @OA\Property(format="string", description="Lietotāja epasts", property="email"),
     * @OA\Property(format="integer", description="", property="provider_id"),
     * @OA\Property(format="integer", description="Lietotāja vecums", property="age"),
     * @OA\Property(format="string", description="Lietotaja dzimšanas diena(MM-DD-YYYY)", property="birthday"),
     * @OA\Property(format="boolean", description="Lietotāja dzimums: true - sieviete, false - vīrietis", property="gender"),
     * @OA\Property(format="string", description="Īss raksts par lietotāju", property="about_me"),
     * @OA\Property(format="string", description="Lietotāja lietojamā valoda", property="language"),
     *
     * @return array<string, mixed>
     */
    public function rules() {
        return [
            'avatar' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'provider_id' => 'required',
            'age' => '',
            'birthday' => 'required',
            'gender' => 'required',
            'about_me' => 'required',
            'language' => 'required',
        ];
    }

}
