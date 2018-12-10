<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

/**
 * @var \Laravel\Lumen\Routing\Router $router
 */
$router->group([
    'middleware' => (env('RATE_LIMIT_ON', false)) ? ['throttle:' . env('RATE_LIMIT', "60,10")] : [],
], function () use ($router) {
    // Children are throttled, X requests per Y minute(s)
    $router->get('/', function () use ($router) {
        return $router->app->version();
    });

    $router->get('/ping', function () {
        return response()->json('pong', 200);
    });
});
