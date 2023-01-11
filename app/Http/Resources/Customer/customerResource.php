<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class customerResource extends  JsonResource{

    public function toArray($request)
    {
        return [
            "id"=>$this->id,
            "name"=>$this->name,
            "since"=>$this->since,
            "revenue"=>$this->firstname,
        ];
    }
}
