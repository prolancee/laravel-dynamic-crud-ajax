<?php

namespace PROLANCEE\DYNAMIC\CRUD\Ajax\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use PROLANCEE\DYNAMIC\CRUD\Ajax\App\Http\Middleware\{
    ValidateIntermediateRoutes,
    ForceJsonResponse,
    Ajax
};
use PROLANCEE\DYNAMIC\CRUD\Ajax\Classes\Responsor\RouterResponder;
use PROLANCEE\Support\Classes\Config\AppMetaData;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $router = $this->app->make('router');
        $startPoint = AppMetaData::getStartPoint(['end'   => '/*'], 'web');

        /* ---------------------------------
         | REGISTER MIDDLEWARE ALIAS
         --------------------------------- */
        $router->aliasMiddleware(
            'prolancee.dynamic.crud.ajax',
            Ajax::class
        );

        /* ---------------------------------
         | PACKAGE MIDDLEWARE GROUP
         --------------------------------- */
        $router->middlewareGroup('prolancee.dynamic.crud.ajax', [
            ValidateIntermediateRoutes::class,
            ForceJsonResponse::class,
            Ajax::class,
        ]);

        /* ---------------------------------
         | RATE LIMITING
         --------------------------------- */
        $this->configureRateLimiting();

        /* ---------------------------------
         | PACKAGE FALLBACK (404)
         --------------------------------- */
        Route::fallback(function () use ($startPoint) {
            if (request()->is($startPoint)) {
                $exception = RouterResponder::validateRoute(
                    'routeNotFound',
                    request()->path()
                );
                if ($exception instanceof JsonResponse) {
                    return $exception;
                }
            }
        });

        /* ---------------------------------
         | CUSTOM 405 HANDLER
         --------------------------------- */
        app(ExceptionHandler::class)
            ->renderable(function (MethodNotAllowedHttpException $e, $req) use ($startPoint) {
                if ($req->is($startPoint)) {
                    $exception = RouterResponder::validateRoute(
                        'methodNotAllowed',
                        $req->path()
                    );
                    return $exception instanceof JsonResponse ? $exception : null;
                }
            });

        /* ---------------------------------
         | ROUTES (SKIP IF CACHED)
         --------------------------------- */
        if ($this->app->routesAreCached()) {
            return;
        }

        /* ---------------------------------
         | LOAD ROUTES
         --------------------------------- */
        Route::prefix(AppMetaData::getStartPoint([], 'web'))
            ->middleware([
                'prolancee.dynamic.crud.ajax',
                'throttle:prolancee-ajax',
            ])
            ->group(__DIR__ . '/../routes/web.php');
    }

    /**
     * Define rate limiting rules for AJAX requests.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('prolancee-ajax', function (Request $req) {
            return Limit::perMinute(60)
                ->by($req->user()?->id ?: $req->ip())
                ->response(fn () => response()->json([
                    'message' => 'Too many AJAX requests. Please slow down.'
                ], 429));
        });
    }
}
