<?php

namespace App\Services\Customer;

use App\Http\Controllers\Base\BaseController;
use App\Models\Customers\Customer;
use Illuminate\Database\QueryException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class registerService extends BaseController{

    /**
     * @param Customer $customer
     */
    public function __construct(public Customer $customer){
        $this->customer=$customer;
    }

    /**
     * New  Customer Register
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register($request):JsonResponse{
        try {
            $this->customer->create([
               "name"       =>  $request->name,
               "email"      =>  $request->email,
               "password"   =>  Hash::make($request->password),
               "since"      =>  $request->since,
               "revenue"    =>  $request->revenue,
               "last_login_ip_address"  => $request->ip()
            ]);
            return $this->sendResponse("registerResponse",["message"=>"Customer created successfully, please login."]);
        }catch (QueryException){
            return $this->sendErrorResponse(["error_message"=>"Your subscription failed."]);
        }
    }


}
