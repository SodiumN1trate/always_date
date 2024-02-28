<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @OA\Schema(
 *     title="Message resource",
 *     description="Message resource",
 *     @OA\Xml(
 *         name="MessageResource"
 *     )
 * )
 */
class MessageResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request) {
        return [
            'user' => $this->user->id,
            'chat_room_id' => $this->chat_room_id,
            'message' => $this->message,
            'date' => Carbon::parse($this->created_at)->format('Y-m-d'),
            'time' => Carbon::parse($this->created_at)->format('H:m'),
        ];
    }

}
