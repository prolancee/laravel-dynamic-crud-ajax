<?php

namespace PROLANCEE\DYNAMIC\CRUD\Ajax\App\Http\Middleware\Base\Validator;

use PROLANCEE\DYNAMIC\CRUD\Ajax\App\Http\Middleware\Base\Validator\Modelers;
use Illuminate\Http\{Request, JsonResponse};
use PROLANCEE\Support\Classes\Database\DBJson;
use PROLANCEE\DYNAMIC\CRUD\Ajax\Classes\Responsor\{ParameterResponder, CatchResponder};
use PROLANCEE\Support\Classes\Routes\RouterTracker;
use RuntimeException;
use Exception;
use Throwable;

class Parameters extends Modelers
{
    /**
     * Validate and process request parameters based on endpoint mapping.
     *
     * @param Request $req
     * @param array $final
     * @return array
     */
    protected static function validateParams(Request $req, array $final) : array|JsonResponse
    {          
        try {
            $fromUri = RouterTracker::getFromUri(); 
            $endpoint = $fromUri['endpoint'] ?? [];
            $requestPath = $fromUri['requestPath'] ?? null;

            $map = [
                /***************Auth************************************************************************************* */
                'auth/register'        => fn() => ParameterResponder::validateAuthParams($final, ['table', 'register']),
                'auth/login'           => fn() => ParameterResponder::validateAuthParams($final, ['table', 'login']),
                'auth/social'          => fn() => ParameterResponder::validateAuthParams($final, ['table', 'social']),
                'auth/logout'          => fn() => ParameterResponder::validateAuthParams($final, ['table', 'logout']),
                'password/reset-token' => fn() => ParameterResponder::validateAuthParams($final, ['', 'passwordResetToken']),
                'change/password'      => fn() => ParameterResponder::validateAuthParams($final, ['table', 'changePassword']),
                
                /***************Crud************************************************************************************ */
                'store/single'  => fn() => ParameterResponder::validateSingleParams($final, ['table', 'store']),
                'store/bulk'    => fn() => ParameterResponder::validateBulkParams($final, ['table', 'store']),
                'fetch/single'  => fn() => ParameterResponder::validateSingleParams($final, ['table', 'fetch']),
                'fetch/builder' => fn() => ParameterResponder::validateFetchBuilderParams($final, 'fetch'),
                'update/single' => fn() => ParameterResponder::validateSingleParams($final, ['table', 'update']),
                'update/bulk'   => fn() => ParameterResponder::validateBulkParams($final, ['table', 'update']),
                'delete/single' => fn() => ParameterResponder::validateSingleParams($final, ['table', 'delete']),
                'delete/bulk'   => fn() => ParameterResponder::validateBulkParams($final, ['table', 'delete']),
                'files/upload'  => fn() => ParameterResponder::validateFilesParams($final, 'files'),
                'files/delete'  => fn() => ParameterResponder::validateFilesParams($final, 'files'),
            ];

            $final = isset($map[$endpoint]) ? $map[$endpoint]() : false;
            if ($final instanceof JsonResponse) return $final;
            
            if (!empty($final['decryption']['table'] ?? null) && !empty($final['decryption']['data'] ?? null)) {
                $processed = self::modelClasses($req, $requestPath, (array) $final);
                if ($processed instanceof JsonResponse) return $processed;
                $prepared = DBJson::prepareForDatabaseMinimal($processed);
                $final['decryption']['data'] = $prepared;
                return $final;
            }
            return $final;

        } catch (RuntimeException $e) {
            return CatchResponder::runtime($e->getMessage());
        } catch (Exception $e) {
            return CatchResponder::generic($e->getMessage());
        } catch (Throwable $e) {
            return CatchResponder::throwable($e->getMessage());
        }
    }
}