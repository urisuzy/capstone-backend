<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'rating' => $this->rating,
            'review' => $this->review,
            'image' => $this->image ? config('app.url') . "/{$this->image}" : null,
            // 'username' => $this->user->username,
            // 'profile' => $this->user->profile,
            'user' => new UserResource($this->user)
        ];
    }
}
