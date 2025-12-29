<?php

use Illuminate\Support\Facades\Route;
use PROLANCEE\DYNAMIC\CRUD\Ajax\App\Http\Controllers\AjaxController;
use PROLANCEE\DYNAMIC\CRUD\Ajax\Classes\Routes\RouteRegistrar;

/*
|--------------------------------------------------------------------------
| PROLANCEE DYNAMIC CRUD AJAX WEB ROUTES
|--------------------------------------------------------------------------
| - All routes run under middleware: prolancee.dynamic.crud.ajax
| - Throttling applied via custom limiter: prolancee-ajax
*/

$middleware = [
    'prolancee.dynamic.crud.ajax',
    'throttle:prolancee-ajax',
];

/*
|--------------------------------------------------------------------------
| AJAX Protected Routes
|--------------------------------------------------------------------------
*/
RouteRegistrar::register(
    $middleware,
    AjaxController::class,
    'ajax'
);
