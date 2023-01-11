<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    /**
     * @param $message
     * @param $result
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponse($message, $result)
    {
        $response = ['success' => true,'data' => $result,'message' => $message];
        return response()->json($response, 200);

    }

    /**
     * @param $error
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendErrorResponse($error)
    {
        $response = ['success' => false, 'message' => $error,];
        return response()->json($response, 401);
    }
}
