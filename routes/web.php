<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return response()->json([
        "version"   => "v1.0",
        "status"    => true,
        "message"   => "To create an order, please add products to your cart."
    ]);
});
