<?php

use App\Http\Controllers\Customer\registerController;
use App\Http\Controllers\Customer\loginController;
use App\Http\Controllers\Baskets\basketsController;
use App\Http\Controllers\Products\productsController;
use App\Http\Controllers\Orders\orderController;
use Illuminate\Support\Facades\Route;

Route::prefix("v1")->group(function (){
    Route::prefix("auth")->group(function (){
        Route::post("login",[loginController::class,"login"]);
        Route::post("register",[registerController::class,"register"]);
    });
    Route::prefix("product")->group(function (){
       Route::get("list",[productsController::class,"productList"]);
    });
    Route::prefix("baskets")->middleware("jwt")->group(function (){
        Route::post("create",[basketsController::class,"addBasket"]);
        Route::post("remove",[basketsController::class,"removeBasket"]);
        Route::get("list",[basketsController::class,"listBasket"]);
    });
    Route::prefix("order")->middleware("jwt")->group(function (){
        Route::post("preCreate",[orderController::class,"preOrderCreate"]);
        Route::post("create",[orderController::class,"orderCreate"]);
        Route::get("list",[orderController::class,"myOrders"]);
    });

    Route::get('/',function (){
        return response()->json([
            "version"   => "v1.0",
            "status"    => true,
            "message"   => "To create an order, please add products to your cart."
        ]);
    });

});
