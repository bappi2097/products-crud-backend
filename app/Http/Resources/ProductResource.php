<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "description" => $this->description,
            "price" => $this->price,
            "image" => filter_var($this->image, FILTER_VALIDATE_URL) ? $this->image : asset($this->image),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
