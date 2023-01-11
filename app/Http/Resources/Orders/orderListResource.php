<?php

namespace App\Http\Resources\Orders;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class orderListResource extends  JsonResource{

    public function toArray($request)
    {
        return [
            "id"                => $this->id,
            "customerId"        => $this->customerId,
            "items"             => orderItemResource::collection(json_decode($this->items)),
            "total"             => $this->total,
            "createdAt"         => Carbon::createFromFormat("Y-m-d H:i:s",$this->created_at)->format('d-m-Y H:i:s'),
            "discounts"         => orderDiscountResource::collection($this->discounts)
        ];
    }
}
