<?php

namespace PROLANCEE\DYNAMIC\CRUD\Ajax\App\Http\Controllers\Base;

use Illuminate\Http\JsonResponse;
use PROLANCEE\DYNAMIC\CRUD\Ajax\Classes\Responsor\ServiceResponder;

class BaseHelperController
{
    // -------------------------------
    // Handle standardizedResponse DRY
    // -------------------------------
    protected function standardizedResponse($result, string $operation, array $option = []): JsonResponse
    {
        // -------------------------------
        // File upload response
        // -------------------------------
        if ($result && $operation === 'file-upload') {
            $successful = array_filter($result, fn($u) => $u['status'] ?? false);
            $failed     = array_filter($result, fn($u) => !$u['status']);
            $code       = $result[0]['code'] ?? 500;

            $publicUrls = array_map(fn($u) => isset($u['stored_path']) ? asset("storage/{$u['stored_path']}") : null, $successful);

            $code    = count($successful) ? 200 : $code;
            $message = count($successful) ? "{$operation}-success" : "{$operation}-error";

            $responseOption = [
                'upload'  => [
                    'stored'     => $successful,
                    'public_url' => $publicUrls,
                    'errors'     => $failed,  
                ],
                'operation'      => $operation
            ];

            return ServiceResponder::response([
                'res'     => count($successful) ? 'success' : 'error',
                'code'    => $code,
                'message' => $message,
                'option'  => array_merge($option, $responseOption)
            ]);
        }

        // -------------------------------
        // File upload Delete response
        // -------------------------------
        if ($result && ($operation === 'file-delete')) {
            return ServiceResponder::response([
                'res'     => 'success',
                'code'    => 200,
                'message' => "{$operation}-success",
                'option'  => array_merge($option, [
                    'file_path' => $result,
                    'operation'   => $operation
                ])
            ]);
        }

        // -------------------------------
        // Encrypt / Decrypt response
        // -------------------------------
        if ($result && ($operation === 'encrypt' || $operation === 'decrypt')) {
            return ServiceResponder::response([
                'res'     => 'success',
                'code'    => 200,
                'message' => "{$operation}-success",
                'option'  => array_merge($option, [
                    $operation => $result,
                    'operation'  => $operation
                ])
            ]);
        }

        // -------------------------------
        // Default error response
        // -------------------------------
        return ServiceResponder::response([
            'res'     => 'error',
            'code'    => 500,
            'message' => "{$operation}-error",
            'option'  => array_merge($option, [
                'operation' => $operation
            ])
        ]);
    }

    // -------------------------------
    // Handle queryResponse DRY
    // -------------------------------
    protected function queryResponse($result, string $operation): JsonResponse
    {
        $result = ServiceResponder::validateQuery($result);
        if ($result instanceof JsonResponse) {
            return $result;
        }

        // --------------------------------------------
        // Warn response (all false)
        // --------------------------------------------
        if (
            $result === false ||
            (is_array($result) && !empty($result) && count(array_filter($result)) === 0)
        ) {
            return ServiceResponder::response([
                'res'     => 'warn',
                'code'    => 206,
                'message' => "{$operation}-warn",
                'option'  => [
                    'operation' => $operation,
                ],
            ]);
        }

        // --------------------------------------------
        // Normal response
        // --------------------------------------------
        if (
            (is_array($result) && !empty($result)) ||
            (is_object($result) && !empty((array) $result))
        ) {
            $resultArray = (array) $result;

            $data         = $resultArray['data'] ?? null;
            $auth         = $resultArray['auth'] ?? null;
            $notified     = $resultArray['notified'] ?? null;

            $hasData = !empty($data);

            $code = match (true) {
                $hasData && $operation === 'store' => 201,
                $hasData                           => 200,
                in_array($operation, ['login', 'changePassword']) => 401,
                default                            => 404,
            };

            $responseData = [
                'res'     => $hasData ? 'success' : 'error',
                'code'    => $code,
                'message' => $hasData
                    ? "{$operation}-success"
                    : "{$operation}-error",
                'option'  => [
                    'operation' => $operation,
                ],
            ];

            if ($hasData) {
                $responseData['option']['data'] = $data;
            } else {
                $responseData['option']['auth'] = $auth;
            }

            if (!empty($notified)) {
                $responseData['option']['notified'] = $notified;
            }

            return ServiceResponder::response($responseData);
        }

        // --------------------------------------------
        // Fallback error
        // --------------------------------------------
        return ServiceResponder::response([
            'res'     => 'error',
            'code'    => 500,
            'message' => "{$operation}-error",
            'option'  => [
                'operation' => $operation,
            ],
        ]);
    }
}