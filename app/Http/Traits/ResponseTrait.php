<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

trait ResponseTrait
{
    /**
     * Success response method.
     *
     * @param string $message
     * @param mixed $result
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponse(string $message, $result = [], int $code = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data'    => $result,
        ];

        // Handle pagination if result is a LengthAwarePaginator
        if ($result instanceof LengthAwarePaginator) {
            $response['data'] = $result->items();
            $response['pagination'] = [
                'current_page' => $result->currentPage(),
                'per_page' => $result->perPage(),
                'total' => $result->total(),
                'last_page' => $result->lastPage(),
            ];
        }

        return response()->json($response, $code);
    }

    /**
     * Return error response.
     *
     * @param string|array $error
     * @param int $code
     * @param array $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendError($error, int $code = 422, array $errors = []): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => is_array($error) ? implode(', ', $error) : $error,
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        // Log error for debugging
        Log::error('API Error Response', [
            'message' => $response['message'],
            'code' => $code,
            'errors' => $errors
        ]);

        return response()->json($response, $code);
    }

    /**
     * Return validation error response.
     *
     * @param array $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendValidationError(array $errors): JsonResponse
    {
        return $this->sendError('Validation failed', 422, $errors);
    }

    /**
     * Return unauthorized error response.
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendUnauthorized(string $message = 'Unauthorized access'): JsonResponse
    {
        return $this->sendError($message, 401);
    }

    /**
     * Return forbidden error response.
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendForbidden(string $message = 'Access forbidden'): JsonResponse
    {
        return $this->sendError($message, 403);
    }

    /**
     * Return not found error response.
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendNotFound(string $message = 'Resource not found'): JsonResponse
    {
        return $this->sendError($message, 404);
    }

    /**
     * Return server error response.
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendServerError(string $message = 'Internal server error'): JsonResponse
    {
        return $this->sendError($message, 500);
    }

    /**
     * Return too many requests error response.
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendTooManyRequests(string $message = 'Too many requests'): JsonResponse
    {
        return $this->sendError($message, 429);
    }

    /**
     * Return created response.
     *
     * @param string $message
     * @param mixed $result
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendCreated(string $message, $result = []): JsonResponse
    {
        return $this->sendResponse($message, $result, 201);
    }

    /**
     * Return no content response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendNoContent(): JsonResponse
    {
        return response()->json(null, 204);
    }

    /**
     * Return bad request response.
     *
     * @param string $message
     * @param array $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendBadRequest(string $message, array $errors = []): JsonResponse
    {
        return $this->sendError($message, 400, $errors);
    }
} 