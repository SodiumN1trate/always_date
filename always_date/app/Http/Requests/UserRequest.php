<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="User request",
 *      description="",
 *      type="object",
 *      required={
 *          "firstname",
 *          "lastname",
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
     * @OA\Property(format="string", description="Lietotāja pilnais vārds", property="firstname"),
     * @OA\Property(format="string", description="Lietotāja pilnais uzvārds", property="lastname"),
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
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'sometimes|email|unique:users',
            'provider_id' => 'sometimes',
            'age' => '',
            'birthday' => 'required|date|before:-18 years|after:-100 years',
            'gender' => 'required',
            'about_me' => 'required|max:1024',
            'language' => 'required',
        ];
    }

}
