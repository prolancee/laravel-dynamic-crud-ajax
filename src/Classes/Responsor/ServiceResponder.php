<?php

namespace PROLANCEE\DYNAMIC\CRUD\Ajax\Classes\Responsor;

use Illuminate\Http\JsonResponse;
use PROLANCEE\DYNAMIC\CRUD\Ajax\Classes\Responsor\HelperResponder;
use PROLANCEE\DYNAMIC\CRUD\Ajax\Exceptions\Handler\AjaxException;

class ServiceResponder extends HelperResponder
{
    /**
     * Processes and returns error responses for known exceptions.
     *
     * @param array|object $data
     * @return array|bool
     */
    public static function validateQuery($result): array|bool|JsonResponse
    {
        try {
            foreach (['query_exception', 'redis_exception'] as $ex) {
                if (isset($result[$ex])) {
                    throw new AjaxException($result[$ex]);
                }
            }

            return $result;
        } catch (AjaxException $e) {
            return $e->toJsonResponse();
        }
    }

    /**
     * Builds a standardized API response based on the result type.
     *
     * Supported $res values:
     * - success
     * - error
     * - warn
     * - info
     *
     * @param array $data
     * @return JsonResponse
     */
    public static function response(array $data): JsonResponse
    {
        $defaults = [
            'res'     => null,
            'code'    => 500,
            'message' => null,
            'option'  => []
        ];

        $data = array_merge($defaults, $data);

        $res     = $data['res'];
        $code    = $data['code'];
        $message = $data['message'];
        $option  = $data['option'];

        switch ($res) {
            case 'success':
                return self::toJsonResponse(
                    self::formatResponse(true, $code, $message, $option)
                );

            case 'warn':
            case 'info':
                return self::toJsonResponse(
                    self::formatResponse(true, $code, $message, $option)
                );

            case 'error':
                return self::toJsonResponse(
                    self::formatResponse(false, $code, $message, $option)
                );

            default:
                return self::toJsonResponse(
                    self::formatResponse(false, 500, 'Invalid response type.', [
                        'process' => false,
                        'error'   => true
                    ])
                );
        }
    }
}
