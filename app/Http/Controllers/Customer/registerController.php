<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Base\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\registerRequest;
use App\Services\Customer\registerService;

class registerController extends BaseController
{
    /**
     * @param registerService $registerService
     */
    public function __construct(public registerService $registerService){
        $this->registerService=$registerService;
    }

    /**
     * Customer Register
     * @param registerRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(registerRequest $request){
        return $this->registerService->register($request);
    }

}
