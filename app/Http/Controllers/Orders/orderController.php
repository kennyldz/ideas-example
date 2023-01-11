<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Services\Orders\orderService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class orderController extends Controller
{

    /**
     * @param orderService $orderService
     */
    public function __construct(public orderService $orderService){
        $this->orderService=$orderService;
    }

    /**
     * Order Pre Create
     * @return \Illuminate\Http\JsonResponse
     */
    public function preOrderCreate():JsonResponse{
     return $this->orderService->preOrderCreate();
    }

    /**
     * Order Create
     * @param Request $request
     * @return JsonResponse
     */
    public function orderCreate(Request $request):JsonResponse{
        return $this->orderService->orderCreate($request);
    }

    /**
     * Order My Order
     * @return JsonResponse
     */
    public function myOrders():JsonResponse{
       return $this->orderService->orderList();
    }

}
