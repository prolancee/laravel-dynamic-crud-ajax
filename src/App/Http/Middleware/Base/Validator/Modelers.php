<?php

namespace PROLANCEE\DYNAMIC\CRUD\Ajax\App\Http\Middleware\Base\Validator;

use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\LazyCollection;
use App\Models\Prolancee\Modeler;
use PROLANCEE\Support\Classes\Crypto\Encrypter;
use PROLANCEE\DYNAMIC\CRUD\Ajax\Classes\Responsor\{ModelerResponder, CatchResponder};
use PROLANCEE\Support\Classes\Config\AppMetaData;
use RuntimeException;
use Exception;
use Throwable;

class Modelers
{
    /**
     * Finds and returns the model instance that matches the given table name.
     *
     * @param array  $models     List of model class names.
     * @param string $tableName  Table name.
     * @return object|null       Matching model instance or null.
     */
    private static function findModel(array $models, string $tableName): object
    {
        foreach ($models as $modelClass) {
            $model = new $modelClass();
            if ($model->secureTable() === $tableName) {
                return $model;
            }
        }
    }

    /**
     * Processes a table (single or bulk rows), validates fillable and mandatory fields,
     * and returns either filtered data or validation errors.
     *
     * @param Request $req         HTTP request instance.
     * @param string  $tableName   Table name.
     * @param array   $data        Input table data (single or bulk).
     * @param array   $models      List of model class names.
     * @param string  $requestPath Current request URI path.
     * @param array   $encryption  Encryption mapping.
     * @param array   $encTable    Encrypted table mapping.
     * @param int     $maxErrors   Max errors before truncation.
     * @param int     $chunkSize   Chunk size for bulk processing.
     * @return array               ['errors'=>..., 'data'=>...] or ['errors'=>..., 'data_summary'=>...]
     */
    private static function processTable(
        Request $req,
        string $tableName,
        array $data,
        array $models,
        string $requestPath,
        array $encryption,
        array $encTable,
        int $maxErrors = 1000,
        int $chunkSize = 1000
    ): array {
        $model = self::findModel($models, $tableName);

        $isBulk    = array_values($data) === $data;
        $iterator  = $isBulk
            ? (class_exists(LazyCollection::class) ? LazyCollection::make($data)->chunk($chunkSize) : array_chunk($data, $chunkSize))
            : [[$data]];

        $errors        = [];
        $processed     = 0;
        $resultsSample = [];

        foreach ($iterator as $chunk) {
            $rows = $chunk instanceof LazyCollection ? $chunk->all() : $chunk;

            foreach ($rows as $row) {
                $processed++;
                $filtered = $model->filterRow((array)$row);
                $missing  = $model->checkMandatoryRow(
                    $filtered, 
                    $model, 
                    $requestPath, 
                    $tableName, 
                    $encryption,
                    AppMetaData::getStartPoint(['start' => '/', 'end' => '/'], 'web')
                );

                if (!empty($missing)) {
                    $errors[] = [
                        'table'   => $encTable[$tableName],
                        'index'   => $processed - 1,
                        'missing' => $missing
                    ];

                    if (count($errors) >= $maxErrors) {
                        $errors[] = ['note' => 'Max error limit reached; truncated'];
                        break 2;
                    }
                    continue;
                }

                if (count($resultsSample) < 10) {
                    $resultsSample[] = $filtered;
                }
            }
        }
        return !$isBulk
            ? ['errors' => $errors, 'data' => $resultsSample[0] ?? []]
            : ['errors' => $errors, 'data_summary' => 
            ['processed' => $processed, 'sample' => $resultsSample]];
    }

    /**
     * Normalize payload, validate model fields, return filtered data or error.
     * 
     * @param  string   $tbl
     * @param  array    $data
     * @return array
     */
    private static function normalizePayload($data, $tbl): array
    {
        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        if (!is_array($data)) {
            return [];
        }

        return $data[$tbl] ?? $data;
    }

    /**
     * Main entry point: maps decrypted payload to models, 
     * applies validation & filtering, and aggregates results.
     *
     * @param Request $req   HTTP request instance.
     * @param array   $final Decrypted payload with 'table' and 'data'.
     * @return array|JsonResponse Filtered data or JSON error response.
     */
    protected static function modelClasses(Request $req, $requestPath, array $final): array|JsonResponse
    {
        try {
            $payload    = $final['decryption'] ?? [];
            $encryption = $final['encryption'] ?? [];
            $tableName  = $payload['table'] ?? null;
            $data       = $payload['data'] ?? [];
            $models     = Modeler::modelClasses();
            $encTable   = [];

            $models = ModelerResponder::validateModelClasses(Modeler::class, $models, $final);
            if ($models instanceof JsonResponse) return $models;

            $allErrors = [];
            $results   = [];

            $tables    = is_array($tableName) ? $tableName : [$tableName];
            $tables    = array_filter($tables, fn($t) => !empty($t));

            foreach ($tables as $key => $tbl) {
                $encTable[$tbl] = Encrypter::getPayloadEncryptTable($encryption, $tbl, $key);

                $data = self::normalizePayload($payload['data'] ?? null, $tbl);
                $result = self::processTable($req, $tbl, $data, $models, $requestPath, $encryption, $encTable);

                $allErrors = array_merge($allErrors, $result['errors'] ?? []);
                $results[$tbl] = $result['data'] ?? $result['data_summary'] ?? [];
            }

            if (!empty($allErrors)) {
                $isOnlyValidation = !collect($allErrors)->contains(fn($err) =>
                    isset($err['error']) && in_array($err['error'], ['request_exception'])
                );

                return ModelerResponder::validation($allErrors);
            }
            return count($tables) === 1 ? reset($results) : $results;

        } catch (RuntimeException $e) {
            return CatchResponder::runtime($e->getMessage());
        } catch (Exception $e) {
            return CatchResponder::generic($e->getMessage());
        } catch (Throwable $e) {
            return CatchResponder::throwable($e->getMessage());
        }
    }
}