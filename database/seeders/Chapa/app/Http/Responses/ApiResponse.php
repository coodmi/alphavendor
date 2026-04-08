<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * API Response Helper.
 *
 * Provides consistent response formatting for API endpoints.
 */
class ApiResponse
{
    /**
     * Return a success response.
     *
     * @param  mixed  $data
     */
    public static function success($data = null, string $message = 'Success', int $statusCode = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return an error response.
     *
     * @param  mixed  $data
     */
    public static function error(
        string $message = 'An error occurred',
        int $statusCode = 400,
        ?array $errors = null,
        $data = null
    ): JsonResponse {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return a validation error response.
     */
    public static function validationError(
        array $errors,
        string $message = 'Validation failed'
    ): JsonResponse {
        return self::error($message, 422, $errors);
    }

    /**
     * Return a not found response.
     */
    public static function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return self::error($message, 404);
    }

    /**
     * Return an unauthorized response.
     */
    public static function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return self::error($message, 401);
    }

    /**
     * Return a forbidden response.
     */
    public static function forbidden(string $message = 'Forbidden'): JsonResponse
    {
        return self::error($message, 403);
    }

    /**
     * Return a server error response.
     *
     * @param  mixed  $data
     */
    public static function serverError(
        string $message = 'Internal server error',
        $data = null
    ): JsonResponse {
        return self::error($message, 500, null, $data);
    }

    /**
     * Return a paginated response.
     */
    public static function paginated(
        LengthAwarePaginator $paginator,
        string $message = 'Success'
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'from' => $paginator->firstItem(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'to' => $paginator->lastItem(),
                'total' => $paginator->total(),
            ],
            'links' => [
                'first' => $paginator->url(1),
                'last' => $paginator->url($paginator->lastPage()),
                'prev' => $paginator->previousPageUrl(),
                'next' => $paginator->nextPageUrl(),
            ],
        ]);
    }

    /**
     * Return a collection response.
     *
     * @param  Collection|array  $collection
     */
    public static function collection($collection, string $message = 'Success'): JsonResponse
    {
        return self::success($collection, $message);
    }

    /**
     * Return a created response.
     *
     * @param  mixed  $data
     */
    public static function created($data = null, string $message = 'Resource created successfully'): JsonResponse
    {
        return self::success($data, $message, 201);
    }

    /**
     * Return an updated response.
     *
     * @param  mixed  $data
     */
    public static function updated($data = null, string $message = 'Resource updated successfully'): JsonResponse
    {
        return self::success($data, $message);
    }

    /**
     * Return a deleted response.
     */
    public static function deleted(string $message = 'Resource deleted successfully'): JsonResponse
    {
        return self::success(null, $message);
    }

    /**
     * Return a no content response.
     */
    public static function noContent(): JsonResponse
    {
        return response()->json(null, 204);
    }

    /**
     * Return a custom response.
     */
    public static function custom(array $data, int $statusCode = 200): JsonResponse
    {
        return response()->json($data, $statusCode);
    }
}
