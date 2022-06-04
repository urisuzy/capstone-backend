<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TourismResource extends JsonResource
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
            'name' => $this->name,
            'city' => $this->city,
            'price' => $this->price,
            'rating' => $this->rating,
            'time_minutes' => $this->time_minutes,
            'coordinate' => $this->coordinate,
            'thumbnail' => $this->thumbnail,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'category' => new CategoryResource($this->category),
            'user' => new UserResource($this->user),
            'reviews' => ReviewResource::collection($this->reviews)
        ];
    }
}
