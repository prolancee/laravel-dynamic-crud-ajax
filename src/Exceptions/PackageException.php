<?php

namespace PROLANCEE\DYNAMIC\CRUD\Ajax\Exceptions;

use Illuminate\Http\Request;
use Throwable;

class PackageException
{
    protected Request $request;

    /**
     * Constructor.
     *
     * @param Request $request The current HTTP request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Render exception as structured array.
     *
     * @param Throwable|null $exception
     * @param array $payload
     * @return array
     */
    public function render(array $payload = [], ?Throwable $exception = null): array
    {
        return $this->errorResponse($payload, $exception);
    }

    /**
     * Build structured error array.
     *
     * @param Throwable $exception
     * @param array $payload
     * @return array
     */
    private function errorResponse(array $payload, ?Throwable $exception = null): array
    {
        $code = $this->getHttpCode($payload);
        $debug = config('prolancee.dynamic.crud.ajax.debug', false);

        if ($debug) {
            $requestInfo = [
                'Request' => [
                    'URL'    => $this->request->fullUrl(),
                    'Method' => $this->request->getMethod(),
                ],
                'Browser' => config('app.debug', false)
                    ? $this->sanitizeUserAgent($this->request->header('user-agent'))
                    : 'CLIENT',
            ];

            return [
                'status'       => in_array($code, [200, 201]),
                'code'         => $code,
                'exception'    => array_merge($payload, [
                    "timestamp" => now()->toDateTimeString(),
                ]),
                'request_info' => $requestInfo,
                'debug'        => config('app.debug', false),
            ];
        }

        return [
            'status'  => false,
            'code'    => 500,
            'message' => 'Server Error',
            'debug'   => $debug,
        ];
    }

    /**
     * Get HTTP status code from payload.
     *
     * @param array $payload
     * @return int
     */
    private function getHttpCode(array &$payload): int
    {
        if (!empty($payload['code'])) {
            $code = (int) $payload['code'];
            unset($payload['status'], $payload['code']);
            return $code;
        }
        return 500;
    }

    /**
     * Extract client/product name from User-Agent.
     *
     * @param string|null $ua
     * @return string
     */
    private function sanitizeUserAgent(?string $ua): string
    {
        return $ua ? preg_replace('/^([^\s\/]+).*$/', '$1', $ua) : 'N/A';
    }
}