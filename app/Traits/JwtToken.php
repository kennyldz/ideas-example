<?php

namespace App\Traits;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

trait JwtToken{
    /**
     * JWT Token Set
     * @param $name
     * @param $userID
     * @param $email
     * @return string
     */

    public function setJwtSession($name,$customerID,$email){
        return  JWT::encode(["name"=>$name, "email"=>$email,"customer_id"=>$customerID, "iat"=>time(), "exp"=>time()+60*60*60+1],env('JWT_TOKEN')??JWT::JWT_TOKEN,'HS256');
    }




    /**
     * Get Jwt Token
     * @return mixed
     */
    public function getJwtSession(){
        $token=request()->bearerToken()??false;
        if ($token==false){ //if there is no token
            return response()->json(['status'=>false,'error_message'=>'Unauthorized access'],401);
        }
        $decode=JWT::decode($token,new Key(env('JWT_TOKEN')??JWT::JWT_TOKEN,'HS256'));
        return $decode->customer_id;
    }
}
