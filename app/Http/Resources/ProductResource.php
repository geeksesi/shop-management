<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'creator' => $this->whenLoaded('creator', new UserResource($this->creator)),
            'category_id' => $this->whenLoaded('category', new CategoryResource($this->category)),
            'price' => $this->price,
            'type' => $this->type
        ];
    }
}
