<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @OA\Schema(
 *     title="Chat room resource",
 *     description="Chat room resource",
 *     @OA\Xml(
 *         name="ChatRoomResource"
 *     )
 * )
 */
class ChatRoomResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request) {
        return [
            'id' => $this->id,
            'user1_id' => $this->user1_id,
            'user2_id' => $this->user2_id,
        ];
    }

}
