<?php

namespace Cyberbrains\Filemanager\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class ApiController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * success response method.
     *
     * @param $result
     * @param string $message
     * @return JsonResponse
     */

    public function sendResponse($result, string $message = "Success!"): JsonResponse
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
        ];
        return response()->json($response);
    }

    /**
     * index response method.
     *
     * @param $result
     * @param array $meta
     * @param $filters
     * @return JsonResponse
     */
    public function sendData($result, array $meta, $filters): JsonResponse
    {
        $response = [
            'success' => true,
            'data' => $result,
            'filter' => $filters,
            '_meta' => $meta,
        ];

        return response()->json($response);
    }

    /**
     * return error response.
     *
     * @param string $error
     * @param array $errorMessages
     * @param int $code
     * @return JsonResponse
     */
    public function sendError(string $error, array $errorMessages = [], int $code = 500): JsonResponse
    {

        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}