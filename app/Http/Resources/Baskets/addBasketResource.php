<?php

namespace App\Http\Resources\Baskets;

use Illuminate\Http\Resources\Json\JsonResource;

class addBasketResource extends  JsonResource{

    public function toArray($request)
    {
        return [
            "basket_id"     =>$this->id,
            "productId"     =>$this->productId,
            "productName"   =>$this->products->name,
            "quantity"      =>$this->quantity,
            "unitPrice"     =>$this->unit_price,
            "totalPrice"    =>$this->total_price,
            "cartCreationDate"     =>$this->created_at,
            "cartUpdatedDate" => $this->updated_at
        ];
    }
}
