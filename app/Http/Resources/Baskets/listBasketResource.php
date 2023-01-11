<?php

namespace App\Http\Resources\Baskets;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class listBasketResource extends  JsonResource{

    public function toArray($request)
    {
        return [
            "basket_id"     =>$this->id,
            "productId"     =>$this->productId,
            "productName"   =>$this->products->name,
            "quantity"      =>$this->quantity,
            "unitPrice"     =>$this->unit_price,
            "totalPrice"    =>$this->total_price,
            "cartCreationDate"=> Carbon::createFromFormat("Y-m-d H:i:s",$this->created_at)->format('d-m-Y H:i:s'),
            "cartUpdatedDate" => Carbon::createFromFormat("Y-m-d H:i:s",$this->updated_at)->format('d-m-Y H:i:s')
        ];
    }
}
