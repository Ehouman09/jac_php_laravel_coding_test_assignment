<?php

namespace App\Traits;


trait JsonResponseTrait
{
   
    /**
     * Generates a JSON response with the given code, message, and data.
     *
     * @param $code The HTTP status code for the response.
     * @param  $message The message to include in the response.
     * @param $data The data to include in the response.
     * @return The JSON response.
     */
    public function jsonResponse($code, $message, $data)
    {
        return response(
            [
                'code' => $code,
                'status' => $code == 200 || $code == 201 ? 'success' : 'error',
                'message' => $message,
                'data' => $data,
            ],
            $code,
        );
    }
 
}
