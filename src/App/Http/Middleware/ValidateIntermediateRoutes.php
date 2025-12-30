<?php

namespace PROLANCEE\DYNAMIC\CRUD\Ajax\App\Http\Middleware;

use Illuminate\Http\{Request, JsonResponse};
use PROLANCEE\DYNAMIC\CRUD\Ajax\Classes\Responsor\RouterResponder;
use Closure;

class ValidateIntermediateRoutes
{
    public function handle(Request $req, Closure $next)
    {
        if (!config('prolancee.dynamic.crud.ajax.intermediate_route_enabled', true) && $req->is('prolancee/web/*')) {

            $storedRoutesFile = storage_path('app/private/prolancee/routes.json');
            if (!file_exists($storedRoutesFile)) {
                return $next($req);
            }

            $storedRoutesJson = json_decode(file_get_contents($storedRoutesFile), true);
            $storedRoutes     = $storedRoutesJson['routes']['web'] ?? [];

            if (! $storedRoutes) {
                return $next($req);
            }

            $storedRoutes = array_map(function ($routes) {
                return array_map(function ($r) {
                    return $this->normalizeRoute($r);
                }, $routes);
            }, $storedRoutes);

            $currentUri = $this->normalizeRoute('/' . ltrim($req->path(), '/'));
            $currentMethod = strtoupper($req->method());

            if (!in_array($currentUri, $storedRoutes[$currentMethod] ?? [])) {
                $exception = RouterResponder::validateRoute('intermediateRouteNotAllowed', $currentUri);
                if ($exception instanceof JsonResponse) return $exception;
            }
        }
        return $next($req);
    }

    /**
     * Normalize route: remove over-escaped slashes, multiple slashes, backslashes.
     */
    protected function normalizeRoute(string $route): string
    {
        while (strpos($route, '\\') !== false) {
            $route = stripslashes($route);
        }
        $route = str_replace(['\\/', '\/'], '/', $route);
        $route = preg_replace('#/+#', '/', $route);
        return '/' . ltrim($route, '/');
    }
}
