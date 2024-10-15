<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;

trait JsonResponsable
{
    /**
     * Success response method.
     *
     * @param mixed $result
     * @param string|null $message
     *
     * @return JsonResponse
     */
    public function sendResponse(mixed $result = null, ?string $message = null): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message ?? __('Success'),
            'data'    => $result,
        ];

        return response()->json(
            Arr::whereNotNull($response),
            Response::HTTP_OK
        );
    }

    /**
     * Return error response.
     *
     * @param string|null $error
     * @param mixed $errorMessages
     * @param int $code
     *
     * @return JsonResponse
     */
    public function sendError(?string $error = null, mixed $errorMessages = [], int $code = 404): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error ?? __('Error!'),
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
