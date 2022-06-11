<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LifeSchoolCommentResource extends JsonResource
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
            'owner_id' => $this->owner_id,
            'description' => $this->description,
            'likes' => $this->likes,
            'dislikes' => $this->dislikes,
        ];
    }
}
