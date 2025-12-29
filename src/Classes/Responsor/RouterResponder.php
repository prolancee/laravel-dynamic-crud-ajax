<?php

namespace PROLANCEE\DYNAMIC\CRUD\Ajax\Classes\Responsor;

use Illuminate\Http\JsonResponse;
use PROLANCEE\DYNAMIC\CRUD\Ajax\Classes\Responsor\HelperResponder;
use PROLANCEE\DYNAMIC\CRUD\Ajax\Exceptions\Handler\AjaxException;

class RouterArrayFormat extends HelperResponder
{
    /**
     * Returns an error response when the requested HTTP method is not allowed.
     *
     * @param string|null $uri
     * @return array
     */
    public static function methodNotAllowed(string $uri = null): array
    {
        return self::formatResponse(
            false,
            405,
            'The requested route does not exist or does not support this HTTP method.',
            [
                'error' => 'route_exception',
                'hint'  => 'Check CRUD Blade docs under prolancee/web/*. Use correct method.',
                'uri'   => $uri
            ]
        );
    }

    /**
     * Returns an error response when the requested route is not found.
     *
     * @param string|null $uri
     * @return array
     */
    public static function routeNotFound(string $uri = null): array
    {
        return self::formatResponse(
            false,
            404,
            'The requested route does not exist or does not support this HTTP method.',
            [
                'error' => 'route_exception',
                'hint'  => 'Check CRUD Blade docs under prolancee/web/*. Use correct method.',
                'uri'   => $uri
            ]
        );
    }

    /**
     * Returns an error response for intermediate routes that are not allowed.
     *
     * @param string|null $uri
     * @return array
     */
    public static function intermediateRouteNotAllowed(string $uri = null): array
    {
        return self::formatResponse(
            false,
            403,
            'Route not registered',
            [
                'error' => 'route_exception',
                'hint'  => 'Intermediate route enabled (false).',
                'uri'   => $uri
            ]
        );
    }
}

class RouterResponder extends RouterArrayFormat
{
    /**
     * Validate route and return JSON response.
     *
     * @param string $method
     * @param string|null $uri
     * @return JsonResponse
     */
    public static function validateRoute(string $method, string $uri = null): JsonResponse
    {
        try {
            $response = match ($method) {
                'methodNotAllowed' => self::methodNotAllowed($uri),
                'routeNotFound' => self::routeNotFound($uri),
                'intermediateRouteNotAllowed' => self::intermediateRouteNotAllowed($uri),
                default => self::routeNotFound($uri),
            };

            throw new AjaxException($response);

        } catch (AjaxException $e) {
            return $e->toJsonResponse();
        }
    }
}