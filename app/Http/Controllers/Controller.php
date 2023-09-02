<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function successResponse($message, $response_code = 200)
    {
        $response = [
            'success' => true,
            'message_type' => 'success',
            'message' => $message,
            'code' => $response_code
        ];
        return response()->json($response, $response_code);
    }

    public function successFullApiResponse($message, $data, $response_code = 200)
    {
        $response = [
            'success' => true,
            'message_type' => 'success',
            'message' => $message,
            'data' => $data,
            'code' => $response_code
        ];
        return response()->json($response, $response_code);
    }
    public function notFound($message, $data, $response_code = 200)
    {
        $response = [
            'success' => true,
            'message_type' => 'success',
            'message' => $message,
            'data' => $data,
            'code' => $response_code,
        ];
        return response()->json($response, $response_code);
    }
    public function noDataFoundApiResponse($message, $response_code = 200)
    {
        $response = [
            'success' => true,
            'message_type' => 'success',
            'message' => $message,
            'code' => $response_code,
        ];
        return response()->json($response, $response_code);
    }
    public function validateDataApiResponse($message, $response_code = 200)
    {
        $response = [
            'success' => false,
            'message_type' => 'error',
            'message' => $message,
            'code' => $response_code,
        ];
        return response()->json($response, $response_code);
    }
    public function failedApiResponse($message, $response_code)
    {
        $response = [
            'success' => false,
            'message_type' => 'error',
            'message' => $message,
            'code' => $response_code,
        ];
        return response()->json($response, $response_code);
    }
    public function unAuthorizedApiResponseForProfile($message, $mix_panel_project_token, $response_code)
    {
        $response = [
            'success' => false,
            'message_type' => 'error',
            'message' => $message,
            'mix_panel_project_token' => $mix_panel_project_token,
            'code' => $response_code,
        ];
        return response()->json($response, $response_code);
    }
    public function errorApiResponse($message, $response_code = 500)
    {
        $response = [
            'success' => false,
            'message_type' => 'error',
            'error_message' => $message,
            'code' => $response_code,
        ];
        return response()->json($response, $response_code);
    }
}
