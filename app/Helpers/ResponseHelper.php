<?php

namespace App\Helpers;

class ResponseHelper
{
    public static function jsonResponse($status, $message, $data = null)
    {
        $response = [
            'status' => $status,
            'message' => $message,
        ];

        if (!is_null($data)) {
            $response['data'] = $data;
        }
        return response()->json($response, 200);
    }
    /**
     * JSON response with user data
     *
     * @param bool $status
     * @param string $message
     * @param mixed $data
     * @return \Illuminate\Http\JsonResponse
     */
    public static function jsonResponseWithData($status, $message, $data)
    {
        $response = [
            'status' => $status,
            'message' => $message,
            'data' => $data,  // Include the data in the response
        ];

        return response()->json($response, 200);
    }
}

