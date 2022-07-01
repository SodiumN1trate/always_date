<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @OA\Schema(
 *     title="Message resource",
 *     description="Message resource",
 *     @OA\Xml(
 *         name="MessageResource"
 *     )
 * )
 */
class MessageResource extends JsonResource
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
            'name' => $this->user()->first()->name,
            'chat_room_id' => $this->chat_room_id,
            'message' => $this->message,
        ];
    }
}
