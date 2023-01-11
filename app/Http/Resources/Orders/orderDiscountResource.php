<?php

namespace App\Http\Resources\Orders;

use Illuminate\Http\Resources\Json\JsonResource;

class orderDiscountResource extends  JsonResource{

    public function toArray($request)
    {
        return [
            "orderId"                   => $this->orderId,
            "discounts"                 => json_decode($this->discounts),
            "totalDiscount"             => $this->total_discount,
            "discountedTotal"           => $this->discounted_total,
            "totalWithoutDiscount"      => $this->total_without_discount
        ];
    }
}
