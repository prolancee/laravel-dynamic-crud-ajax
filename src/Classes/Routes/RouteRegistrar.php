<?php

namespace PROLANCEE\DYNAMIC\CRUD\Ajax\Classes\Routes;

use Illuminate\Support\Facades\Route;
use PROLANCEE\DYNAMIC\CRUD\Ajax\Classes\Routes\ConfigRouter;

/**
 * Register dynamic CRUD routes.
 *
 * @param array  $middleware  Middleware stack for the generated routes.
 * @param string $controller  Target controller class.
 * @param string $prefixBase  Base route prefix.
 * @param int    $maxDepth    Maximum nested segment levels allowed.
 */
final class RouteRegistrar
{
    public static function register(
        array $middleware, 
        string $controller, 
        string $prefixBase, 
        int $maxDepth = 3 
    ): void {
        Route::middleware($middleware)->group(function () use ($controller, $prefixBase, $maxDepth) {

            $routes = ConfigRouter::get($controller, $prefixBase);

            for ($level = 1; $level <= $maxDepth; $level++) {

                $segments = array_map(
                    fn($i) => "{v{$i}}",
                    range(1, $level)
                );

                $prefix = '/' . implode('/', $segments);

                foreach ($routes as $suffix => $config) {

                    Route::{strtolower($config['method'])}(
                        "$prefix/$suffix",
                        $config['action']
                    );
                }
            }
        });
    }
}
