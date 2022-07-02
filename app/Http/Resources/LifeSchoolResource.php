<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @OA\Schema(
 *     title="Life school resource",
 *     description="Life school resource",
 *     @OA\Xml(
 *         name="LifeSchoolResource"
 *     )
 * )
 */
class LifeSchoolResource extends JsonResource
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
            'title' => $this->title,
            'gender' => $this->gender,
            'description' => $this->description,
        ];
    }
}
