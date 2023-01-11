<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Base\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\loginRequest;
use App\Services\Customer\loginService;

class loginController extends BaseController
{

    /**
     * @param loginService $loginService
     */
    public function __construct(public loginService $loginService){
        $this->loginService=$loginService;
    }

    /**
     * Customer Login
     * @param loginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(loginRequest $request){
        return $this->loginService->login($request);
    }
}
