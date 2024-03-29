<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller as Controller;

class BaseController extends Controller
{
    // TODO: Implement response transformers if has more time (need to check on Laravel docs)

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message)
    {
    	$response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
    	$response = [
            'success' => false,
            'message' => $error,
        ];

        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    /**
     * Sends response
     *
     * @param  BaseRepository  $baseRepository
     * @return \Illuminate\Http\Response
     */
    public function returnResponse($baseRepository)
    {
        if(!$baseRepository->success) {
            return $this->sendError($baseRepository->message, $baseRepository->data, $baseRepository->code);
        }

        return $this->sendResponse($baseRepository->data, $baseRepository->message);
    }
}