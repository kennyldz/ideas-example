<?php

namespace App\Services\Stock;

use App\Enums\statusEnum;
use App\Http\Controllers\Base\BaseController;
use App\Http\Resources\Baskets\addBasketResource;
use App\Models\Baskets\Basket;
use App\Models\Products\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class stockService extends BaseController{

    public $products; /** @var array Product Info */
    public $productId; /** @var int Product Id */
    public $quantity; /** @var int Product quantity */
    public $customerId; /** @var int Customer ID  */
    public $deletedProducts=[]; /** @var array products deleted in case of insufficient stock before sale  */
    public $selectedProducts=[]; /** @var array selected Products */

    /**
     * @param Product $product
     * @param Basket $basket
     */
    public function __construct(public Product $product,public Basket $basket){
        $this->product=$product;
        $this->basket=$basket;
    }

    /**
     * Get Product Info
     * @return void
     */
    public function getProduct():void{
        try {
            $this->products=$this->product->where('id',$this->productId)->where('status',statusEnum::ACTIVE->value)->firstOrFail(); //Product control
        }catch (ModelNotFoundException){ //if there is not product info
            throw new HttpResponseException($this->sendErrorResponse(['error_message'=>"The product you wanted to add could not be found, please try again. Contact the authorized person."]));
        }
    }

    /**
     * Check Basket Stock
     * @return void
     */
    public function stockControl():void{
      if ($this->quantity>=$this->products->stock){ //if there is not enough stock
       throw new HttpResponseException($this->sendErrorResponse(["error_message"=>"There is not enough stock for the product you added."]));
      }
    }


    /**
     * It was created for stock control before payment.
     * @return void
     */
    public function preOrderStockControl():void{
        try {
           $baskets=$this->basket->where('customerId',$this->customerId)->get(); //fetch added products
            if (empty($baskets)){
                throw new HttpResponseException($this->sendErrorResponse(["error_message"=>"There are no products in your cart. Please add items to your cart first."]));
            }
                foreach ($baskets as $basket){
                    if ($basket->quantity>=$basket->products->stock){
                        $this->basket->where('id',$basket->id)->delete();
                        $this->deletedProducts[]=[
                            "productId"     => $basket->productId,
                            "product_name"  => $basket->products->name,
                            "quantity"      => $basket->quantity,
                            "unitPrice"     => $basket->unit_price,
                            "total"         => $basket->total_price,
                            "status"        => "Removed from cart due to lack of stock."
                        ];
                    }else{
                        $this->selectedProducts[]=[
                            "basket_id"      => $basket->id,
                            "productId"      => $basket->productId,
                            "product_name"   => $basket->products->name,
                            "quantity"       => $basket->quantity,
                            "unitPrice"      => $basket->unit_price,
                            "total"          => $basket->total_price,
                        ];
                    }
                }

        }catch (QueryException){
            throw new HttpResponseException($this->sendErrorResponse(["error_message"=>"There are no products in your cart. Please add items to your cart first."]));
        }
    }


    /**
     * Stock Remove
     * @param $orderProducts
     * @return void
     */
    public function removeStock($orderProducts):void{
        try {
            foreach (json_decode($orderProducts) as $product){
                $this->product->where('id', $product->productId)->decrement('stock', $product->quantity);
            }
        }catch (QueryException $exception){
            Log::critical('stock reduction could not be achieved after ordering.'.print_r($exception));
        }
    }

}
