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
            'quantity' => (integer) $this->quantity,
            'weight' => $this->weight,
            'creator' => $this->user,
            'category_id' => $this->category->only('id','name'),
            'price' => $this->price,
            'sale_price' => $this->sale_price,
            'type' => $this->type
        ];
    }
}
