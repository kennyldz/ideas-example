<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Services\Products\productsService;
use Illuminate\Http\JsonResponse;

class productsController extends Controller
{

    /**
     * @param productsService $productsService
     */
    public function __construct(public productsService $productsService){
        $this->productsService=$productsService;
    }


    /**
     * Product List
     * @return \Illuminate\Http\JsonResponse
     */
    public function productList():JsonResponse{
        return $this->productsService->productList(); //Get Product List
    }
}
