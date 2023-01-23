<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        #return parent::toArray($request);
        return  $this->collection->map(function ($item) use($request) {
            return [
                "id" => $item->id ,
                "title" => $item->title ,
                "description" => $item->description ,
                "children" => new CategoryCollection($item->children) ,
            ];
        });
    }
}
