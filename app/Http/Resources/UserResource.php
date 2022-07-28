<?php

namespace App\Http\Resources;

use App\Models\LifeSchool;
use Illuminate\Http\Resources\Json\JsonResource;

/** @OA\Schema(
 *     title="User resource",
 *     description="User resource",
 *     @OA\Xml(
 *         name="UserResource"
 *     )
 * )
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'avatar' => $this->avatar,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'provider_id' => $this->provider_id,
            'wallet' => $this->wallet,
            'rating' => $this->rating,
            'age' => $this->age,
            'birthday' => $this->birthday,
            'gender' => $this->gender,
            'about_me' => $this->about_me,
            'language' => $this->language,
            'read_school_exp' => $this->read_school_exp.'/'.LifeSchool::where('gender', $this->gender)->count(),
            'is_vip' => $this->is_vip,
        ];
    }
}
