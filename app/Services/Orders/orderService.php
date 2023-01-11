<?php

namespace App\Services\Orders;

use App\Http\Controllers\Base\BaseController;
use App\Http\Resources\Orders\orderListResource;
use App\Models\Orders\Order;
use App\Services\Baskets\basketService;
use App\Services\Discounts\discountService;
use App\Services\Stock\stockService;
use App\Traits\JwtToken;
use Illuminate\Database\QueryException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class orderService extends BaseController{

    use JwtToken;

    public $orders; /** @var array Order List */
    public $customerId; /** @var int Customer Id */

    /**
     * @param discountService $discountService
     * @param stockService $stockService
     * @param Order $order
     * @param basketService $basketService
     */
    public function __construct(public discountService $discountService,public stockService $stockService,public Order $order,public basketService $basketService){
        $this->discountService=$discountService;
        $this->stockService=$stockService;
        $this->basketService=$basketService;
        $this->order=$order;
    }


    /**
     * It was created to display the last data before ordering.
     * @return \Illuminate\Http\JsonResponse
     */
    public function preOrderCreate():JsonResponse{
       $this->stockService->customerId=$this->getJwtSession(); //Set customer Id
       $this->basketService->checkBasket($this->getJwtSession()); //Check Basket
       $this->stockService->preOrderStockControl(); //Get Pre order stock control
       $this->setDiscountTotal(); //Set Discount Total
       return $this->sendResponse('preOrderResponse',["message"=>"You can perform the transaction to get the products in your cart.","basket"=>$this->stockService->selectedProducts,"out_of_stock"=>$this->stockService->deletedProducts,"discounts"=>$this->discountService->discounts,"total"=>$this->discountService->totalOrderPrice,"totalDiscount"=>$this->discountService->totalDiscount,"discountedTotal"=>$this->discountService->discountedTotal]);
    }


    /**
     * Create Order
     * @param $request
     * @return JsonResponse
     */
    public function orderCreate($request):JsonResponse{
        try {
            $customerId=$this->getJwtSession(); //Set customer Id
            $this->stockService->customerId=$customerId; //Set customer Id
            $this->basketService->checkBasket($customerId); //Check Basket
            $this->stockService->preOrderStockControl(); //Get Pre order stock control
            $this->setDiscountTotal(); //Set Discount Total
            $order=$this->order->create([
                "customerId"     => $customerId,
                "items"          => json_encode($this->stockService->selectedProducts),
                "total"          => $this->discountService->totalOrderPrice,
                "ip_address_of_transaction"  => $request->ip()
            ]);
            $this->stockService->removeStock(json_encode($this->stockService->selectedProducts)); //Remove Stock
            $this->basketService->removeBasketList(json_encode($this->stockService->selectedProducts)); //Remove Basket List
            $this->discountService->createDiscount($order->id,$customerId,$this->discountService->discounts,$this->discountService->totalDiscount,$this->discountService->discountedTotal,$this->discountService->totalOrderPrice);
            return $this->sendResponse('createOrderResponse',["message"=>"Your order has been successfully created.","basket"=>$this->stockService->selectedProducts,"discounts"=>$this->discountService->discounts,"total"=>$this->discountService->totalOrderPrice,"totalDiscount"=>$this->discountService->totalDiscount,"discountedTotal"=>$this->discountService->discountedTotal]);
        }catch (QueryException $exception){
            return $this->sendErrorResponse(["error_message"=>"Your purchase could not be made."]);
        }
    }

    /**
     * Set Discount and Price transaction
     * @return void
     */
    private function setDiscountTotal():void{
        try {
            $this->discountService->percentOverDiscount(); //discount control
            $this->discountService->buyCountGetFree(); //discount control
            $this->discountService->buyCountGetDiscount(); //discount control
        }catch (\RuntimeException){
            throw new HttpResponseException($this->sendErrorResponse(["error_message"=>"Your transaction could not be processed while the discount is being applied."]));
        }
    }

    /**
     * GetMy Orders Details
     * @return JsonResponse
     */
    public function orderList():JsonResponse{
        try{
            $this->customerId=$this->getJwtSession(); //Get Customer ID
            $this->orders=$this->order->where('customerId',$this->customerId)->with('discounts')->get();
            return $this->sendResponse('myOrdersResponse',["message"=>"Your orders are listed","orders"=> orderListResource::collection($this->orders)]);
        }catch (QueryException $exception){
            throw new HttpResponseException($this->sendErrorResponse(["error_message"=>$exception->getMessage()."An error occurred while fetching my orders."]));
        }
    }


}
