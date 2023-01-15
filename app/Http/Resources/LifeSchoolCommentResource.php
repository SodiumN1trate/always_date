<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/** @OA\Schema(
 *     title="Life school comment resource",
 *     description="Life School comment resource",
 *     @OA\Xml(
 *         name="LifeSchoolCommentResource"
 *     )
 * )
 */
class LifeSchoolCommentResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request) {
        return [
            'id' => $this->id,
            'author' => [
                'firstname' => $this->user->firstname,
                'lastname' => $this->user->lastname,
                'avatar' => $this->user->avatar,
            ],
            'description' => $this->description,
            'article_id' => $this->article_id,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d'),
            'votes' => $this->votes,
            'voted' => $this->voters->contains('rater_id', auth()->user()->id) ? $this->voters->map(function ($voter) {
                error_log($voter);
                if ($voter->rater_id === auth()->user()->id) {
                    return $voter;
                }
            })[0]->rating : null,
        ];
    }

}
