<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @OA\Schema(
 *     title="Match log resource",
 *     description="Match log resource",
 *     @OA\Xml(
 *         name="MatchLogResource"
 *     )
 * )
 */
class MatchLogResource extends JsonResource
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
            'user_1' => $this->user_1,
            'user_2' => $this->user_2,
            'is_match' => $this->is_match,
        ];
    }
}
