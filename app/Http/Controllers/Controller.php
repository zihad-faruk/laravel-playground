<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function successResponse($resource, $statusCode = 200, $message = 'Success', $api='',$extra=[]): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $resource,
            'requested_by' => [
                'role' => request()->user()->user_type ?? ''
            ]
        ];
        if(!empty($extra)){
            $response['extra'] = $extra;
        }
        Log::info($api."|SUCCESS Response:".json_encode($response));
        return response()->json($response, $statusCode);
    }

    public function errorResponse(
        $message = 'Something went wrong.',
        int $statusCode = 400,
        $exception = null, $api=''
    ): JsonResponse
    {
        $response =  [
            'success' => false,
            'message' => $message ?? 'Something went wrong.',
            'data' => $exception,
            'requested_by' => [
                'role' => request()->user()->user_type ?? ''
            ]
        ];
        Log::info($api."|ERROR Response:".json_encode($response));
        return response()->json($response, $statusCode);
    }

}
