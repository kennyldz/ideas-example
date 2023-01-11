<?php

namespace App\Http\Controllers\Baskets;

use App\Http\Controllers\Controller;
use App\Http\Requests\Basket\addBasketRequest;
use App\Http\Requests\Basket\removeBasketRequest;
use App\Services\Baskets\basketService;
use App\Services\Stock\stockService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class basketsController extends Controller
{
    /**
     * @param basketService $basketService
     */
    public function __construct(public basketService $basketService){
        $this->basketService=$basketService;
    }

    /**
     * Add Basket
     * @param Request $request
     * @param stockService $stockService
     * @return \Illuminate\Http\JsonResponse
     */
    public function addBasket(addBasketRequest $request,stockService $stockService):JsonResponse{
              $stockService->productId=$request->product_id; //Set product Id
              $stockService->quantity=$request->quantity; //Set Product Quantity
              $stockService->getProduct(); //Product control
              $stockService->stockControl(); //Stock control
              $this->basketService->product=$stockService->products; //Set Product
      return  $this->basketService->add($request); //Add product to cart
    }

    /**
     * Remove Basket
     * @param removeBasketRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeBasket(removeBasketRequest $request):JsonResponse{
      return $this->basketService->remove($request->basket_id); //remove product from cart
    }

    /**
     * List Basket
     * @return JsonResponse
     */
    public function listBasket():JsonResponse{
      return $this->basketService->list(); //get Basket List
    }


}
