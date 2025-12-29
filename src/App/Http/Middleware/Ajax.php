<?php

namespace PROLANCEE\DYNAMIC\CRUD\Ajax\App\Http\Middleware;

use PROLANCEE\DYNAMIC\CRUD\Ajax\App\Http\Middleware\Base\Decryptor;
use Illuminate\Http\{Request, JsonResponse};
use Closure;

class Ajax extends Decryptor
{
    public function handle(Request $req, Closure $next)
    {
        $paramsCheck = $this->decryptParams($req);
        if ($paramsCheck instanceof JsonResponse) return $paramsCheck;

        return $next($req);
    }
}