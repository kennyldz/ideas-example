<?php

namespace App\Http\Resources\Products;

use Illuminate\Http\Resources\Json\JsonResource;


class productListResource extends  JsonResource{

    public function toArray($request)
    {
        return [
            "id"          => $this->id,
            "name"        => $this->name,
            "category"    => $this->category,
            "price"       => $this->price,
            "stock"       => $this->stock,
        ];
    }
}
