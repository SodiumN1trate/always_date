<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @OA\Schema(
 *     title="Report log resource",
 *     description="Report log resource",
 *     @OA\Xml(
 *         name="ReportLogResource"
 *     )
 * )
 */
class ReportLogResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request) {
        return [
            'id' => $this->id,
            'reporter_id' => new UserResource($this->reporter),
            'profile_id' =>  new UserResource($this->profile),
            'report_type' => new ReportTypeResource($this->reportType()->first()),
        ];
    }

}
