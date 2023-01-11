<?php

namespace App\Http\Resources\Orders;

use Illuminate\Http\Resources\Json\JsonResource;

class orderItemResource extends  JsonResource{

    public function toArray($request)
    {
        return [
            "productId"       => $this->productId,
            "productName"     => $this->product_name,
            "quantity"        => $this->quantity,
            "unitPrice"       => $this->unitPrice,
            "total"           => $this->total
        ];
    }
}
