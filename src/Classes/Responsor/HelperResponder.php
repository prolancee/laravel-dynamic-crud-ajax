<?php

namespace PROLANCEE\DYNAMIC\CRUD\Ajax\Classes\Responsor;

use Illuminate\Http\JsonResponse;
use PROLANCEE\Support\Classes\Http\HttpStatusMapper;

class HelperResponder
{
    /**
     * Returns a Array response.
     *
     * @param array $payload
     * @return array
     */
    public static function toArray(array $payload): array
    {
        return $payload;
    }

    /**
     * Returns a JSON response.
     *
     * @param array $res
     * @return JsonResponse
     */
    public static function toJsonResponse(array $payload): JsonResponse
    {
        return response()->json($payload, $payload['code'] ?: 500);
    }

    /**
     * Builds a standardized response structure.
     *
     * @param bool $status
     * @param int|string $code
     * @param string|null $message
     * @param array $extra
     * @return array
     */
    protected static function formatResponse(
        bool $status,
        int $code,
        string $message = null,
        array $extra = []
    ): array {
        $base = [];

        $base['status']  = $status;
        $base['code']    = $code;
        $base['title']   = HttpStatusMapper::text($code);
        $base['message'] = $message;
        
        return array_merge($base, ['option' => $extra]);
    }
}