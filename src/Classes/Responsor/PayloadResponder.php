<?php

namespace PROLANCEE\DYNAMIC\CRUD\Ajax\Classes\Responsor;

use Illuminate\Http\JsonResponse;
use PROLANCEE\DYNAMIC\CRUD\Ajax\Classes\Responsor\HelperResponder;

class PayloadResponder extends HelperResponder
{
    /**
     * Returns an error response when the request payload is too large.
     *
     * @param array $extra
     * @return string|JsonResponse
     */
    public static function requestPayloadTooLarge(array $extra = []): JsonResponse
    {
        return self::toJsonResponse(
            self::formatResponse(
                false,
                413,
                'Request payload too large. Increase PHP limits.',
                array_merge([
                    'title' => 'Payload Too Large',
                    'error' => 'request_exception'
                ], $extra)
            )
        );
    }
}
