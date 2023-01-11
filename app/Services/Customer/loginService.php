<?php

namespace App\Services\Customer;

use App\Enums\statusEnum;
use App\Http\Controllers\Base\BaseController;
use App\Http\Resources\Customer\customerResource;
use App\Models\Customers\Customer;
use App\Traits\JwtToken;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class loginService extends BaseController{

    use JwtToken;
    private $customerInfo; /** @var array Customer Info */

    /**
     * @param Customer $customer
     */
    public function __construct(public Customer $customer)
    {
        $this->customer=$customer;
    }

    /**
     * Login Control
     * @param $request
     * @return JsonResponse
     */
    public function login($request):JsonResponse{
        try {
            $this->customerInfo=$this->customer->where('email',$request->email)->where('status',statusEnum::ACTIVE->value)->firstOrFail();
            return $this->passwordCheck($request->password); //Password  Check
        }catch (ModelNotFoundException){
           return $this->sendErrorResponse(["error_message"=>"User Not Found"]);
        }
    }


    /**
     * Password Control
     * @param $customerPassword
     * @return \Illuminate\Http\JsonResponse
     */
    private function passwordCheck($customerPassword):JsonResponse{
        try {
            if(Hash::check($customerPassword,$this->customerInfo->password)){ //if the password is correct
                $token=$this->setJwtSession($this->customerInfo->name,$this->customerInfo->id,$this->customerInfo->email); //Set Token
                return $this->sendResponse('loginResponse',['message'=>'Logged In',"customer"=>new customerResource($this->customerInfo),"token"=>$token]);
            }else{ //if the password is not correct
                return $this->sendErrorResponse(['error_message'=>"Your password is incorrect, please try again."]);
            }
        }catch (\Exception ){
            return $this->sendErrorResponse(['error_message'=>"Sorry, an error has occurred, please try again later."]);
        }
    }

}
