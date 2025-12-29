<?php

namespace PROLANCEE\DYNAMIC\CRUD\Ajax\App\Http\Middleware\Base;

use PROLANCEE\DYNAMIC\CRUD\Ajax\App\Http\Middleware\Base\Validator\Parameters;
use Illuminate\Http\{Request, JsonResponse};
use PROLANCEE\Support\Classes\Crypto\Decrypter;
use PROLANCEE\DYNAMIC\CRUD\Ajax\Classes\Responsor\CatchResponder;
use RuntimeException;
use Exception;
use Throwable;

class Decryptor extends Parameters
{
    /**
     * Decrypts request data and prepares it for DB.
     *
     * @param Request $req
     * @return void|\Illuminate\Http\JsonResponse
     */
    protected function decryptParams(Request $req)
    {
        if ($req->query('q') ?? $req->input('q')) return;

        try {
            $data  = $req->isJson() ? $req->json()->all() : $req->all();
            $final = $this->decryptArrayIterativeFlat($data);

            $final = self::validateParams($req, $final);
            if ($final instanceof JsonResponse) return $final;

            $req->replace($final);

        } catch (RuntimeException $e) {
            return CatchResponder::runtime($e->getMessage());
        } catch (Exception $e) {
            return CatchResponder::generic($e->getMessage());    
        } catch (Throwable $e) {
            return CatchResponder::throwable($e->getMessage());
        }
    }

    /**
     * Decrypts all keys/values in a multi-dimensional array.
     *
     * @param array $data
     * @return array
     */
    private function decryptArrayIterativeFlat(array $data): array
    {
        $result     = [];
        $encryption = [];
        $stack = [[
            'parent' => &$result,
            'path'   => '',
            'value'  => $data
        ]];

        while (!empty($stack)) {
            $item   = array_pop($stack);
            $parent = &$item['parent'];
            $path   = $item['path'];
            $value  = $item['value'];

            foreach ($value as $k => $v) {
                $decKey = is_string($k) ? $this->safeDecrypt($k) : $k;
                $newPath = $path === '' ? $decKey : $path . '.' . $decKey;

                if (is_string($k)) {
                    $encryption[$newPath . ".__key"] = $k;
                }

                if (is_array($v)) {
                    $parent[$decKey] = [];
                    $stack[] = [
                        'parent' => &$parent[$decKey],
                        'path'   => $newPath,
                        'value'  => $v
                    ];
                } else {
                    if (is_string($v)) {
                        $decVal = $this->safeDecrypt($v);
                        $parent[$decKey] = $decVal;

                        $encryption[$newPath . ".__val"] = $v;
                    } else {
                        $parent[$decKey] = $v;
                    }
                }
            }
        }

        return ['decryption' => $result, 'encryption' => $encryption];
    }

    /**
     * Safely decrypts a string; returns original on failure.
     *
     * @param string $value
     * @return string
     */
    private function safeDecrypt(string $value): string
    {
        try { return Decrypter::decrypt($value); }
        catch (Throwable $e) { return $value; }
    }
}
