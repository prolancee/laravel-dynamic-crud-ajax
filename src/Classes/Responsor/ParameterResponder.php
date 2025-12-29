<?php
namespace PROLANCEE\DYNAMIC\CRUD\Ajax\Classes\Responsor;

use Illuminate\Http\JsonResponse;
use PROLANCEE\Support\Classes\Crypto\Encrypter;
use PROLANCEE\DYNAMIC\CRUD\Ajax\Classes\Responsor\HelperResponder;
use PROLANCEE\DYNAMIC\CRUD\Ajax\Exceptions\Handler\AjaxException;

class ParameterArrayFormat extends HelperResponder
{
    /**
     * Returns an error response when `table` key is missing.
     *
     * @return array
     */
    public static function tableNotFound(): array
    {
        return self::formatResponse(
            false,
            404,
            'The `table` key is undefined.',
            [
                'error' => 'parameter_exception',
                'hint'  => 'The requested table key is missing. Please include a valid `table` in the payload.',
            ]
        );
    }

    /**
     * Returns an error response when `TABLE` key is missing.
     *
     * @return array
     */
    public static function fetchBuilderTableNotFound(): array
    {
        return self::formatResponse(
            false,
            404,
            'The `TABLE` key is undefined.',
            [
                'error' => 'parameter_exception',
                'hint'  => 'The requested TABLE key is missing. Please include a valid `TABLE` in the payload.',
            ]
        );
    }

    /**
     * Returns an error response when `table` parameter is an empty string.
     *
     * @return array
     */
    public static function tableEmptyString(): array
    {
        return self::formatResponse(
            false,
            400,
            'The `table` parameter must not be empty.',
            [
                'error' => 'parameter_exception',
                'hint'  => 'Provide a non-empty string for the `table` parameter.',
            ]
        );
    }

    /**
     * Returns an error response when `TABLE` parameter is an empty string.
     *
     * @return array
     */
    public static function fetchBuilderTableEmptyString(): array
    {
        return self::formatResponse(
            false,
            400,
            'The `TABLE` parameter must not be empty.',
            [
                'error' => 'parameter_exception',
                'hint'  => 'Provide a non-empty string for the `TABLE` parameter.',
            ]
        );
    }

    /**
     * Returns an error response when `table` parameter is not a string.
     *
     * @return array
     */
    public static function tableNotString(): array
    {
        return self::formatResponse(
            false,
            400,
            'The `table` parameter must be a string.',
            [
                'error' => 'parameter_exception',
                'hint'  => 'Invalid type: `table` must be provided as a string.',
            ]
        );
    }

    /**
     * Returns an error response when `TABLE` parameter is not a string.
     *
     * @return array
     */
    public static function fetchBuilderTableNotString(): array
    {
        return self::formatResponse(
            false,
            400,
            'The `TABLE` parameter must be a string.',
            [
                'error' => 'parameter_exception',
                'hint'  => 'Invalid type: `TABLE` must be provided as a string.',
            ]
        );
    }

    /**
     * Returns an error response when `table` parameter is not an array.
     *
     * @return array
     */
    public static function tableNotArray(): array
    {
        return self::formatResponse(
            false,
            400,
            'The `table` parameter must be an array.',
            [
                'error' => 'parameter_exception',
                'hint'  => 'Provide `table` as an array when multiple tables are required.',
            ]
        );
    }

    /**
     * Returns an error response when `table` parameter is an empty array.
     *
     * @return array
     */
    public static function tableEmptyArray(): array
    {
        return self::formatResponse(
            false,
            400,
            'The `table` parameter must not be empty.',
            [
                'error' => 'parameter_exception',
                'hint'  => 'Include at least one valid table name in the `table` array.',
            ]
        );
    }

    /**
     * Returns an error response when `column` key is missing.
     *
     * @return array
     */
    public static function columnNotFound(): array
    {
        return self::formatResponse(
            false,
            404,
            'The `column` key is undefined.',
            [
                'error' => 'parameter_exception',
                'hint'  => 'The `column` key is missing. Please include a valid `column` in the payload.',
            ]
        );
    }

    /**
     * Returns an error response when `column` parameter is an empty string.
     *
     * @return array
     */
    public static function columnEmptyString(): array
    {
        return self::formatResponse(
            false,
            400,
            'The `column` parameter must not be empty.',
            [
                'error' => 'parameter_exception',
                'hint'  => 'Provide a non-empty string for the `column` parameter.',
            ]
        );
    }

    /**
     * Returns an error response when `column` parameter is not a string.
     *
     * @return array
     */
    public static function columnNotString(): array
    {
        return self::formatResponse(
            false,
            400,
            'The `column` parameter must be a string.',
            [
                'error' => 'parameter_exception',
                'hint'  => 'Invalid type: `column` must be provided as a string.',
            ]
        );
    }

    /**
     * Returns an error response when `column` parameter is empty JSON.
     *
     * @return array
     */
    public static function columnEmptyJson(): array
    {
        return self::formatResponse(
            false,
            422,
            'The `column` parameter must not be empty JSON.',
            [
                'error' => 'parameter_exception',
                'hint'  => 'Provide a non-empty JSON object for the `column` parameter.',
            ]
        );
    }

    /**
     * Returns an error response when `column` parameter is not valid JSON.
     *
     * @return array
     */
    public static function columnNotJson(): array
    {
        return self::formatResponse(
            false,
            422,
            'The `column` parameter must be valid JSON.',
            [
                'error' => 'parameter_exception',
                'hint'  => 'Ensure that `column` contains a valid JSON string.',
            ]
        );
    }

    /**
     * Returns an error response when a key is missing in `column` for a given table.
     *
     * @param string $section
     * @param string $table
     * @return array
     */
    public static function cloumnTableKeyNotMatch(string $table): array
    {
        return self::formatResponse(
            false,
            409,
            "Missing `column` for table: $table.",
            [
                'error' => 'parameter_exception',
                'hint'  => "Ensure `column` contains a key for table `$table`.",
            ]
        );
    }

    /**
     * Returns an error response when `unique` key is missing.
     *
     * @return array
     */
    public static function uniqueNotFound(): array
    {
        return self::formatResponse(
            false,
            404,
            'The `unique` key is undefined.',
            [
                'error' => 'parameter_exception',
                'hint'  => 'Include `unique` keys in the request payload.',
            ]
        );
    }

    /**
     * Returns an error response when `unique` parameter is not a string.
     *
     * @return array
     */
    public static function uniqueNotString(): array
    {
        return self::formatResponse(
            false,
            400,
            'The `unique` parameter must be a string.',
            [
                'error' => 'parameter_exception',
                'hint'  => 'Invalid type: `unique` must be provided as strings.',
            ]
        );
    }

    /**
     * Returns an error response when `unique` parameter is an empty string.
     *
     * @return array
     */
    public static function uniqueEmptyString(): array
    {
        return self::formatResponse(
            false,
            400,
            'The `unique` parameter must not be empty.',
            [
                'error' => 'parameter_exception',
                'hint'  => 'Provide valid non-empty string values for `unique`.',
            ]
        );
    }

    /**
     * Returns an error response when `unique` parameter is not an JSON.
     *
     * @return array
     */
    public static function uniqueNotJson(): array
    {
        return self::formatResponse(
            false,
            400,
            'The `unique` parameter must be a JSON.',
            [
                'error' => 'parameter_exception',
                'hint'  => 'Invalid type: `unique` must be provided as JSON.',
            ]
        );
    }

    /**
     * Returns an error response when `unique` parameter is an empty JSON.
     *
     * @return array
     */
    public static function uniqueEmptyJson(): array
    {
        return self::formatResponse(
            false,
            400,
            'The `unique` parameter must not be empty.',
            [
                'error' => 'parameter_exception',
                'hint'  => 'Provide valid non-empty JSON values for `unique`.',
            ]
        );
    }

    /**
     * Returns an error response when `unique` key does not match with CPU key for a table.
     *
     * @param string $table
     * @return array
     */
    public static function uniqueTableKeyNotMatch(string $table): array
    {
        return self::formatResponse(
            false,
            409,
            "Missing `unique` for table: $table.",
            [
                'error' => 'parameter_exception',
                'hint'  => "Ensure `unique` keys match the expected table key for `$table`.",
            ]
        );
    }

    /**
     * Returns an error response when `data` key is missing.
     *
     * @return array
     */
    public static function dataNotFound(): array
    {
        return self::formatResponse(
            false,
            404,
            'The `data` key is undefined.',
            [
                'error' => 'parameter_exception',
                'hint'  => 'The `data` key is missing. Please include valid data in the request payload.',
            ]
        );
    }

    /**
     * Returns an error response when `data` parameter is empty JSON.
     *
     * @return array
     */
    public static function dataNotEmptyJson(): array
    {
        return self::formatResponse(
            false,
            422,
            'The `data` parameter must not be empty JSON.',
            [
                'error' => 'parameter_exception',
                'hint'  => 'Provide non-empty JSON data for the `data` parameter.',
            ]
        );
    }

    /**
     * Returns an error response when `data` parameter is not valid JSON.
     *
     * @return array
     */
    public static function dataNotJson(): array
    {
        return self::formatResponse(
            false,
            422,
            'The `data` parameter must be valid JSON.',
            [
                'error' => 'parameter_exception',
                'hint'  => 'Ensure the `data` parameter is a valid JSON string.',
            ]
        );
    }

    /**
     * Returns an error response when `data` is missing for a given table.
     *
     * @param string $table
     * @return array
     */
    public static function dataTableKeyNotMatch(string $table): array
    {
        return self::formatResponse(
            false,
            409,
            "Missing `data` for table: $table",
            [
                'error' => 'parameter_exception',
                'hint'  => "Ensure `data` contains a key for table `$table`.",
            ]
        );
    }

    /**
     * Returns an error response when `data` format is invalid for a given table.
     *
     * @param string $table
     * @return array
     */
    public static function dataTableTableKeyNotMatch(string $table): array
    {
        return self::formatResponse(
            false,
            409,
            "Invalid `data` format for table: $table",
            [
                'error' => 'parameter_exception',
                'hint'  => "Ensure `data[$table]` is an array with proper format.",
            ]
        );
    }

    /**
     * Returns an error response when `folder` key is missing.
     *
     * @return array
     */
    public static function uploadFolderNotFound(): array
    {
        return self::formatResponse(
            false,
            404,
            "The `folder` key is undefined.",
            [
                'error' => 'parameter_exception',
                'hint'  => 'The `folder` key is missing. Please provide a valid folder in the request.',
            ]
        );
    }

    /**
     * Returns an error response when `folder` parameter is an empty string.
     *
     * @return array
     */
    public static function uploadFolderEmptyString(): array
    {
        return self::formatResponse(
            false,
            400,
            "The `folder` parameter must not be empty.",
            [
                'error' => 'parameter_exception',
                'hint'  => 'Provide a non-empty string for the `folder` parameter.',
            ]
        );
    }

    /**
     * Returns an error response when `folder` parameter is not a string.
     *
     * @return array
     */
    public static function uploadFolderNotString(): array
    {
        return self::formatResponse(
            false,
            400,
            "The `folder` parameter must be a string.",
            [
                'error' => 'parameter_exception',
                'hint'  => 'Invalid type: folder path must be provided as a string.',
            ]
        );
    }

    /**
     * Returns an error response when `password` key is missing.
     *
     * @return array
     */
    public static function passwordNotFound(): array
    {
        return self::formatResponse(
            false,
            404,
            'The `password` key is undefined.',
            [
                'error' => 'parameter_exception',
                'hint'  => 'The requested password key is missing. Please include a valid `password` in the payload.',
            ]
        );
    }

    /**
     * Returns an error response when `password` parameter is an empty string.
     *
     * @return array
     */
    public static function passwordEmptyString(): array
    {
        return self::formatResponse(
            false,
            400,
            'The `password` parameter must not be empty.',
            [
                'error' => 'parameter_exception',
                'hint'  => 'Provide a non-empty string for the `password` parameter.',
            ]
        );
    }

    /**
     * Returns an error response when `password` parameter is not a string.
     *
     * @return array
     */
    public static function passwordNotString(): array
    {
        return self::formatResponse(
            false,
            400,
            'The `password` parameter must be a string.',
            [
                'error' => 'parameter_exception',
                'hint'  => 'Invalid type: `password` must be provided as a string.',
            ]
        );
    }

    /**
     * Returns an error response when `token` key is missing.
     *
     * @return array
     */
    public static function tokenNotFound(): array
    {
        return self::formatResponse(
            false,
            404,
            'The `token` key is undefined.',
            [
                'error' => 'parameter_exception',
                'hint'  => 'The requested token key is missing. Please include a valid `token` in the payload.',
            ]
        );
    }

    /**
     * Returns an error response when `token` parameter is an empty string.
     *
     * @return array
     */
    public static function tokenEmptyString(): array
    {
        return self::formatResponse(
            false,
            400,
            'The `token` parameter must not be empty.',
            [
                'error' => 'parameter_exception',
                'hint'  => 'Provide a non-empty string for the `token` parameter.',
            ]
        );
    }

    /**
     * Returns an error response when `token` parameter is not a string.
     *
     * @return array
     */
    public static function tokenNotString(): array
    {
        return self::formatResponse(
            false,
            400,
            'The `token` parameter must be a string.',
            [
                'error' => 'parameter_exception',
                'hint'  => 'Invalid type: `token` must be provided as a string.',
            ]
        );
    }

    /**
     * Returns an error response when `email` parameter is not a string.
     *
     * @return array
     */
    public static function emailNotFound(): array
    {
        return self::formatResponse(
            false,
            404,
            'The `email` key is undefined.',
            [
                'error' => 'parameter_exception',
                'hint'  => 'The requested email key is missing. Please include a valid `email` in the payload.',
            ]
        );
    }

    /**
     * Returns an error response when `email` parameter is an empty string.
     *
     * @return array
     */
    public static function emailEmptyString(): array
    {
        return self::formatResponse(
            false,
            404,
            'The `email` parameter must not be empty.',
            [
                'error' => 'parameter_exception',
                'hint'  => 'Provide a non-empty string for the `email` parameter.',
            ]
        );
    }

    /**
     * Returns an error response when `email` parameter is not a string.
     *
     * @return array
     */
    public static function emailNotString(): array
    {
        return self::formatResponse(
            false,
            404,
            'The `email` parameter must be a string.',
            [
                'error' => 'parameter_exception',
                'hint'  => 'Invalid type: `email` must be provided as a string.',
            ]
        );
    }
}

class ParameterResponder extends ParameterArrayFormat
{
    /**
     * Validates parameters for auth.
     *
     * @param array $final
     * @param string $option
     * @return array|JsonResponse
     */
    public static function validateAuthParams(array $final, array $options): array | JsonResponse
    {
        try {
            $payload = $final['decryption'] ?? [];

            foreach ($options as $opt) {

                if ($opt === 'table') {
                    self::validateString(
                        $payload,
                        'table',
                        [self::class, 'tableNotFound'],
                        [self::class, 'tableEmptyString'],
                        [self::class, 'tableNotString']
                    );
                }

                if (in_array($opt, ['register'])) {
                    self::validateArray(
                        $payload,
                        'data',
                        [self::class, 'dataNotFound'],
                        [self::class, 'dataNotEmptyJson'],
                        [self::class, 'dataNotJson']
                    );
                }

                if (in_array($opt, ['login'])) {
                    self::validateString(
                        $payload,
                        'column',
                        [self::class, 'columnNotFound'],
                        [self::class, 'columnEmptyString'],
                        [self::class, 'columnNotString']
                    );

                    self::validateString(
                        $payload,
                        'unique',
                        [self::class, 'uniqueNotFound'],
                        [self::class, 'uniqueEmptyString'],
                        [self::class, 'uniqueNotString']
                    );

                    self::validateString(
                        $payload,
                        'password',
                        [self::class, 'passwordNotFound'],
                        [self::class, 'passwordEmptyString'],
                        [self::class, 'passwordNotString']
                    );
                }

                if ($opt === 'passwordResetToken') {
                    self::validateString(
                        $payload,
                        'email',
                        [self::class, 'emailNotFound'],
                        [self::class, 'emailEmptyString'],
                        [self::class, 'emailNotString']
                    );
                }

                if ($opt === 'changePassword') {
                    self::validateString(
                        $payload,
                        'column',
                        [self::class, 'columnNotFound'],
                        [self::class, 'columnEmptyString'],
                        [self::class, 'columnNotString']
                    );

                    self::validateString(
                        $payload,
                        'unique',
                        [self::class, 'uniqueNotFound'],
                        [self::class, 'uniqueEmptyString'],
                        [self::class, 'uniqueNotString']
                    );

                    self::validateString(
                        $payload,
                        'password',
                        [self::class, 'passwordNotFound'],
                        [self::class, 'passwordEmptyString'],
                        [self::class, 'passwordNotString']
                    );

                    self::validateString(
                        $payload,
                        'token',
                        [self::class, 'tokenNotFound'],
                        [self::class, 'tokenEmptyString'],
                        [self::class, 'tokenNotString']
                    );
                }
            }
            return $final;

        } catch (AjaxException $e) {
            return $e->toJsonResponse($e);
        }
    }

    /**
     * Validates parameters for a single-table request.
     *
     * @param array $final
     * @param array $options
     * @return array|JsonResponse
     */
    public static function validateSingleParams(array $final, array $options): array | JsonResponse
    {
        try {
            $payload = $final['decryption'] ?? [];

            foreach ($options as $opt) {

                if ($opt === 'table') {
                    self::validateString(
                        $payload,
                        'table',
                        [self::class, 'tableNotFound'],
                        [self::class, 'tableEmptyString'],
                        [self::class, 'tableNotString']
                    );
                }

                if (in_array($opt, ['update', 'delete', 'fetch'])) {
                    self::validateString(
                        $payload,
                        'column',
                        [self::class, 'columnNotFound'],
                        [self::class, 'columnEmptyString'],
                        [self::class, 'columnNotString']
                    );

                    self::validateString(
                        $payload,
                        'unique',
                        [self::class, 'uniqueNotFound'],
                        [self::class, 'uniqueEmptyString'],
                        [self::class, 'uniqueNotString']
                    );
                }

                if (in_array($opt, ['update', 'store'])) {
                    self::validateArray(
                        $payload,
                        'data',
                        [self::class, 'dataNotFound'],
                        [self::class, 'dataNotEmptyJson'],
                        [self::class, 'dataNotJson']
                    );
                }
            }
            return $final;

        } catch (AjaxException $e) {
            return $e->toJsonResponse($e);
        }
    }

    /**
     * Validates parameters for multi-table requests.
     *
     * @param array $final
     * @param array $options
     * @return array|JsonResponse
     */
    public static function validateBulkParams(array $final, array $options): array | JsonResponse
    {
        try {
            $payload    = $final['decryption'] ?? [];
            $encryption = $final['encryption'] ?? [];
            $tables     = $payload['table'] ?? [];

            foreach ($options as $opt) {

                if ($opt === 'table') {
                    self::validateArrayRoot(
                        $payload,
                        'table',
                        [self::class, 'tableNotFound'],
                        [self::class, 'tableEmptyArray'],
                        [self::class, 'tableNotArray']
                    );
                }

                if (in_array($opt, ['update', 'delete'])) {

                    self::validateArrayRoot(
                        $payload,
                        'column',
                        [self::class, 'columnNotFound'],
                        [self::class, 'columnEmptyJson'],
                        [self::class, 'columnNotJson']
                    );

                    self::validateTableKeysMatch(
                        $tables,
                        $payload['column'],
                        $encryption,
                        [self::class, 'cloumnTableKeyNotMatch']
                    );

                    self::validateArrayRoot(
                        $payload,
                        'unique',
                        [self::class, 'uniqueNotFound'],
                        [self::class, 'uniqueEmptyJson'],
                        [self::class, 'uniqueNotJson']
                    );

                    self::validateTableKeysMatch(
                        $tables,
                        $payload['unique'],
                        $encryption,
                        [self::class, 'uniqueTableKeyNotMatch']
                    );
                }

                if (in_array($opt, ['update', 'store'])) {

                    self::validateArrayRoot(
                        $payload,
                        'data',
                        [self::class, 'dataNotFound'],
                        [self::class, 'dataNotEmptyJson'],
                        [self::class, 'dataNotJson']
                    );

                    foreach ($tables as $key => $tbl) {
                        if (! array_key_exists($tbl, $payload['data'])) {
                            $payloadTable = Encrypter::getPayloadEncryptTable($encryption, $tbl, $key);
                            throw new AjaxException(self::dataTableKeyNotMatch($payloadTable));
                        }

                        if ($payload['data'][$tbl] !== [] && ! is_array($payload['data'][$tbl])) {
                            $payloadTable = Encrypter::getPayloadEncryptTable($encryption, $tbl, $key);
                            throw new AjaxException(self::dataTableTableKeyNotMatch($payloadTable));
                        }
                    }
                }
            }
            return $final;

        } catch (AjaxException $e) {
            return $e->toJsonResponse($e);
        }
    }

    /**
     * Validates parameters for fetch builder.
     *
     * @param array $final
     * @param string $option
     * @return array|JsonResponse
     */
    public static function validateFetchBuilderParams(array $final, string $option): array|JsonResponse
    {
        try {
            $payload = $final['decryption'] ?? [];

            if ($option === 'fetch') {
                if (!array_key_exists('TABLE', $payload)) {
                    throw new AjaxException(self::fetchBuilderTableNotFound());
                }
                if (empty($payload['TABLE'])) {
                    throw new AjaxException(self::fetchBuilderTableEmptyString());
                }
                if (!is_string($payload['TABLE'])) {
                    throw new AjaxException(self::fetchBuilderTableNotString());
                }
            }
            return $final;
            
        } catch (AjaxException $e) {
            return $e->toJsonResponse($e);
        }
    }

    /**
     * Validates parameters for file uploads.
     *
     * @param array $final
     * @param string $option
     * @return array|JsonResponse
     */
    public static function validateFilesParams(array $final, string $option): array | JsonResponse
    {
        try {
            $payload = $final['decryption'] ?? [];

            if ($option === 'files') {
                self::validateString(
                    $payload,
                    'folder',
                    [self::class, 'uploadFolderNotFound'],
                    [self::class, 'uploadFolderEmptyString'],
                    [self::class, 'uploadFolderNotString']
                );
            }
            return $final;

        } catch (AjaxException $e) {
            return $e->toJsonResponse($e);
        }
    }

    /**
     * Validates a required string parameter in payload.
     *
     * @param array    $payload     Decrypted request payload
     * @param string   $key         Required key name
     * @param callable $notFound    Exception callback when key missing
     * @param callable $empty       Exception callback when value empty
     * @param callable $notString   Exception callback when value not string
     *
     * @throws AjaxException
     */
    protected static function validateString(
        array $payload,
        string $key,
        callable $notFound,
        callable $empty,
        callable $notString
    ): void {
        if (! array_key_exists($key, $payload)) {
            throw new AjaxException($notFound());
        }
        if (empty($payload[$key])) {
            throw new AjaxException($empty());
        }
        if (! is_string($payload[$key] ?? null)) {
            throw new AjaxException($notString());
        }
    }

    /**
     * Validates a required array parameter in payload.
     *
     * @param array    $payload     Decrypted request payload
     * @param string   $key         Required key name
     * @param callable $notFound    Exception callback when key missing
     * @param callable $empty       Exception callback when value empty
     * @param callable $notArray    Exception callback when value not array
     *
     * @throws AjaxException
     */
    protected static function validateArray(
        array $payload,
        string $key,
        callable $notFound,
        callable $empty,
        callable $notArray
    ): void {
        if (! array_key_exists($key, $payload)) {
            throw new AjaxException($notFound());
        }
        if (empty($payload[$key])) {
            throw new AjaxException($empty());
        }
        if (! is_array($payload[$key])) {
            throw new AjaxException($notArray());
        }
    }

    /**
     * Validates a root-level array parameter.
     *
     * @param array    $payload     Decrypted request payload
     * @param string   $key         Required root key
     * @param callable $notFound    Exception callback when key missing
     * @param callable $empty       Exception callback when value empty
     * @param callable $notArray    Exception callback when value not array
     *
     * @throws AjaxException
     */
    protected static function validateArrayRoot(
        array $payload,
        string $key,
        callable $notFound,
        callable $empty,
        callable $notArray
    ): void {
        if (! array_key_exists($key, $payload)) {
            throw new AjaxException($notFound());
        }
        if (empty($payload[$key])) {
            throw new AjaxException($empty());
        }
        if (! is_array($payload[$key])) {
            throw new AjaxException($notArray());
        }
    }

    /**
     * Validates that payload keys match provided table list.
     *
     * @param array    $tables             List of table names
     * @param array    $payloadPart        Payload section (column / unique / data)
     * @param array    $encryption         Encryption metadata
     * @param callable $exceptionCallback  Exception callback for mismatch
     *
     * @throws AjaxException
     */
    protected static function validateTableKeysMatch(
        array $tables,
        array $payloadPart,
        array $encryption,
        callable $exceptionCallback
    ): void {
        foreach ($tables as $key => $tbl) {
            if (! array_key_exists($tbl, $payloadPart)) {
                $payloadTable = Encrypter::getPayloadEncryptTable($encryption, $tbl, $key);
                throw new AjaxException($exceptionCallback($payloadTable));
            }
        }
    }
}
