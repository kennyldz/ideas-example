<?php

namespace App\Services\Products;

use App\Enums\statusEnum;
use App\Http\Controllers\Base\BaseController;
use App\Http\Resources\Orders\orderListResource;
use App\Http\Resources\Products\productListResource;
use App\Models\Products\Product;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;

class productsService extends BaseController{


    /**
     * @param Product $product
     */
    public function __construct(public Product $product){
        $this->product=$product;
    }


    /**
     * list active and in stock products
     * @return \Illuminate\Http\JsonResponse
     */
    public function productList():JsonResponse{
        try{
            $products=$this->product->where('status',statusEnum::ACTIVE->value)->where('stock','>',0)->get(); //list active and in stock products
            return $this->sendResponse("productsResponse",["message"=>"products List","products"=>productListResource::collection($products)]);
        }catch(QueryException){
            return $this->sendErrorResponse(["error_message"=>"The products could not be listed."]);
        }
    }


}
