<?php

namespace App\Services\Baskets;

use App\Http\Controllers\Base\BaseController;
use App\Http\Resources\Baskets\addBasketResource;
use App\Http\Resources\Baskets\listBasketResource;
use App\Models\Baskets\Basket;
use App\Models\Products\Product;
use App\Traits\JwtToken;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Js;

class basketService extends BaseController{

    use JwtToken;

    private $request; /** @var array Request */
    public $product; /** @var array Products Info */

    /**
     * @param Basket $basket
     */
    public function __construct(public Basket $basket){
        $this->basket=$basket;
    }

    /**
     * Add product to cart
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add($request):JsonResponse{
        try {
            $customerId=$this->getJwtSession(); //Get customer ID
            $basket=$this->basket->updateOrCreate(
               ["customerId"    => $customerId,
               "productId"      => $request->product_id],
               ["quantity"      => $request->quantity,
               "category"       => $this->product->category,
               "unit_price"     => $this->product->price,
               "total_price"    => round($request->quantity*$this->product->price,2), //Calculate Product Total Price
               "ip_address_of_transaction" => $request->ip()]
            );
            return $this->sendResponse('basketResponse',["message"=>"The product has been successfully added to your cart.","add_product"=>new addBasketResource($basket)]);
        }catch (QueryException){
            return $this->sendErrorResponse(["error_message"=>"An error occurred while adding to cart, please try again."]);
        }
    }

    /**
     * take out of the basket
     * @param $basketId
     * @return JsonResponse
     */
    public function remove($basketId):JsonResponse{
        try {
            $customerId=$this->getJwtSession(); //Get customer ID
            $this->basket->where('customerId',$customerId)->where('id',$basketId)->delete(); //take out of the basket
            return $this->sendResponse('basketResponse',["message"=>"The product has been successfully deleted from the cart."]);
        }catch (QueryException){
            return $this->sendErrorResponse(["error_message"=>"An error occurred while remove to cart, please try again."]);
        }
    }

    /**
     * list of products in Basket
     * @return JsonResponse
     */
    public function list():JsonResponse{
        try {
            $customerId=$this->getJwtSession(); //Get customer ID
            $baskets=$this->basket->where('customerId',$customerId)->get(); //Get Basket List
            return $this->sendResponse('basketListResponse',["message"=>"list of products in your cart.","baskets"=>listBasketResource::collection($baskets)]);
        }catch (QueryException){
            return $this->sendErrorResponse(["error_message"=>"An error occurred while basket list, please try again."]);
        }
    }


    /**
     * Basket List Remove
     * @param $basketLists
     * @return void
     */
    public function removeBasketList($basketLists):void{
        try {
            foreach (json_decode($basketLists) as $basket){
                $this->basket->where('id', $basket->basket_id)->delete();
            }
        }catch (QueryException $exception){
            Log::critical('Basket list reduction could not be achieved after ordering.'.$exception->getMessage());
        }
    }

    /**
     * @param $customerId
     * @return void
     */
    public function checkBasket($customerId)
    {
        try{
            $this->basket->where('customerId',$customerId)->firstOrFail();
        }catch (ModelNotFoundException){
            throw new HttpResponseException($this->sendErrorResponse(["error_message"=>"To create an order, please add products to your cart."]));
        }
    }



}
