<?php

namespace PROLANCEE\DYNAMIC\CRUD\Ajax\Classes\Responsor;

use Illuminate\Http\JsonResponse;
use PROLANCEE\DYNAMIC\CRUD\Ajax\Classes\Responsor\HelperResponder;
use PROLANCEE\DYNAMIC\CRUD\Ajax\Exceptions\Handler\AjaxException;
use PROLANCEE\Support\Classes\Crypto\Encrypter;

class ModelerArrayFormat extends HelperResponder 
{
    /**
     * Error response when the Modeler class does not define a static modelClasses() method.
     *
     * @return array
     */
    public static function modelClassesMethodNotFound(): array
    {
        return self::formatResponse(
            false,
            404,
            'No Eloquent modelClasses method in the class Modeler.',
            [
                'error' => 'model_exception',
                'hint'  => 'Ensure Modeler class defines a static modelClasses() method.'
            ]
        );
    }

    /**
     * Error response when no Eloquent models could be loaded.
     *
     * @return array
     */
    public static function modelNotFound(): array
    {
        return self::formatResponse(
            false,
            404,
            'No Eloquent models could be loaded.',
            [
                'error' => 'model_exception',
                'hint'  => 'Ensure models are properly defined and autoloaded by Composer.'
            ]
        );
    }

    /**
     * Error response when the `$table` property is undefined in the model.
     *
     * @param string $class Model class name
     * @return array
     */
    public static function modelTableNotFound(string $class): array
    {
        return self::formatResponse(
            false,
            404,
            'The `$table` property is undefined.',
            [
                'error' => 'model_exception',
                'hint'  => "Define table property in model '{$class}'."
            ]
        );
    }

    /**
     * Error response when the model table does not match the expected table name.
     *
     * @param string $table Table name
     * @return array
     */
    public static function modelTableNotMatch(string $table): array
    {
        return self::formatResponse(
            false,
            404,
            "Model does not match with table: $table.",
            [
                'error' => 'model_exception',
                'hint'  => "Ensure the model is mapped correctly with table `$table`."
            ]
        );
    }

    /**
     * Error response when the `$table` property is not a string.
     *
     * @param string $class Model class name
     * @return array
     */
    public static function modelTableNotString(string $class): array
    {
        return self::formatResponse(
            false,
            400,
            'The `table` property must be a string.',
            [
                'error' => 'model_exception',
                'hint'  => "The table property in '{$class}' must be a string."
            ]
        );
    }

    /**
     * Error response when the `$table` property is an empty string.
     *
     * @param string $class Model class name
     * @return array
     */
    public static function modelTableEmptyString(string $class): array
    {
        return self::formatResponse(
            false,
            400,
            'The `table` property must not be empty.',
            [
                'error' => 'model_exception',
                'hint'  => "The table property in '{$class}' cannot be empty."
            ]
        );
    }

    /**
     * Error response when the `$fillable` property is undefined.
     *
     * @param string $class Model class name
     * @return array
     */
    public static function modelFillableNotFound(string $class): array
    {
        return self::formatResponse(
            false,
            404,
            'The `fillable` property is undefined.',
            [
                'error' => 'model_exception',
                'hint'  => "Define fillable fields in model '{$class}'."
            ]
        );
    }

    /**
     * Error response when the `$fillable` property is not an array.
     *
     * @param string $class Model class name
     * @return array
     */
    public static function modelFillableNotArray(string $class): array
    {
        return self::formatResponse(
            false,
            422,
            'The `fillable` property must be an array.',
            [
                'error' => 'model_exception',
                'hint'  => "The fillable property in '{$class}' must be an array."
            ]
        );
    }

    /**
     * Error response when the `$fillable` property is an empty array.
     *
     * @param string $class Model class name
     * @return array
     */
    public static function modelFillableEmptyArray(string $class): array
    {
        return self::formatResponse(
            false,
            400,
            'The `fillable` property must not be empty.',
            [
                'error' => 'model_exception',
                'hint'  => "The model '{$class}' has no fillable fields."
            ]
        );
    }

    /**
     * Error response when the `$mandatory` property is undefined.
     *
     * @param string $class Model class name
     * @return array
     */
    public static function modelMandatoryNotFound(string $class): array
    {
        return self::formatResponse(
            false,
            404,
            'The `mandatory` property is undefined.',
            [
                'error' => 'model_exception',
                'hint'  => "Define mandatory fields in model '{$class}' for validation."
            ]
        );
    }
}

class ModelerResponder extends ModelerArrayFormat
{
    /**
     * Validates registered model classes and their properties.
     *
     * @param string     $class   Modeler class
     * @param array|null $models  Registered model classes
     * @param array      $final   Decrypted payload containing table names
     *
     * @return array|JsonResponse
     */
    public static function validateModelClasses($class, array $models, array $final): array|JsonResponse
    {
        $payload    = $final['decryption'] ?? [];
        $encryption = $final['encryption'] ?? [];
        $tables     = is_array($payload['table']) ? $payload['table'] : [$payload['table']];

        try {
            if (!method_exists($class, 'modelClasses')) {
                throw new ApiException(self::modelClassesMethodNotFound());
            }

            if (empty($models)) {
                throw new ApiException(self::modelNotFound());
            }

            foreach ($tables as $tableKey => $tableName) {

                $matched = false; 

                foreach ($models as $modelClass) {

                    $model = new $modelClass();

                    /** ------------------------------
                     *  TABLE VALIDATION
                     * ------------------------------*/
                    if (!property_exists($model, 'table')) {
                        throw new ApiException(self::modelTableNotFound($modelClass));
                    }

                    if (empty($model->secureTable())) {
                        throw new ApiException(self::modelTableEmptyString($modelClass));
                    }

                    if (!is_string($model->secureTable())) {
                        throw new ApiException(self::modelTableNotString($modelClass));
                    }

                    if ($model->secureTable() === $tableName) {
                        $matched = true;

                        /** ------------------------------
                         *  FILLABLE VALIDATION
                         * ------------------------------*/
                        if (!property_exists($model, 'fillable')) {
                            throw new ApiException(self::modelFillableNotFound($modelClass));
                        }

                        if (empty($model->secureFillable())) {
                            throw new ApiException(self::modelFillableEmptyArray($modelClass));
                        }

                        if (!is_array($model->secureFillable())) {
                            throw new ApiException(self::modelFillableNotArray($modelClass));
                        }

                        /** ------------------------------
                         *  MANDATORY VALIDATION
                         * ------------------------------*/
                        if (!property_exists($model, 'mandatory')) {
                            throw new ApiException(self::modelMandatoryNotFound($modelClass));
                        }

                        break;
                    }
                }

                if (!$matched) {
                    $payloadTable = Encrypter::getPayloadEncryptTable($encryption, $tableName, $tableKey);
                    throw new ApiException(self::modelTableNotMatch($payloadTable));
                }
            }

            return $models;

        } catch (ApiException $e) {
            return $e->toJsonResponse($e);
        }
    }

    /**
     * Validation error response for mandatory fields.
     */
    public static function validation(array $allErrors): JsonResponse
    {
        return self::toJsonResponse(
            self::formatResponse(
                false,
                422,
                'Mandatory field validation failed.',
                ['required' => $allErrors]
            )
        );
    }
}