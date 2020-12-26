<?php


namespace App\Traits;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait AppResponder
{
    /**
     * Build success response
     *
     * @param string|array $data
     * @param int          $code
     * @return JsonResponse
     */
    public function successResponse($data, $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json(['data' => $data], $code);
    }

    /**
     * Build error response
     *
     * @param string|array $message
     * @param int          $code
     * @return JsonResponse
     */
    public function errorResponse($message, $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }
}
