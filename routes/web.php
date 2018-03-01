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

function useRateLimit()
{
    return (env('RATE_LIMIT_ON', false))
        ? ['throttle:' . env('RATE_LIMIT', "60,10")]
        : [];
}

/**
 * @var \Laravel\Lumen\Routing\Router $router
 */
$router->group(['middleware' => useRateLimit()], function () use ($router) {
    // Children are throttled, X requests per Y minute(s)
    $router->get('/', function () use ($router) {
        return $router->app->version();
    });

    $router->group(['namespace' => 'v1', 'prefix' => 'v1'], function () use ($router) {
        // Children are within the v1 namespace, as well as API versioned to /v1/*
        // These requests do not require authentication with an api_token
        $router->group([], function () use ($router) {
            // user
            $router->get('user', 'UsersController@all');
            $router->get('user/{id:[0-9]+}', 'UsersController@get');
            $router->post('user', 'UsersController@add');
            $router->get('user/token', 'UsersController@getToken');
            $router->put('user/token', 'UsersController@updateToken');

            // board
            $router->get('board', 'BoardsController@all');
            $router->get('board/{id:[0-9]+}', 'BoardsController@get');

            // thread
            $router->get('thread', 'ThreadsController@all');
            $router->get('thread/{id:[0-9]+}', 'ThreadsController@get');

            // post
            $router->get('post', 'PostsController@all');
            $router->get('post/{id:[0-9]+}', 'PostsController@get');
        });

        $router->group(['middleware' => ['auth:token']], function () use ($router) {
            // These requests require an api_token to be set in basic-auth password,
            $router->get('auth', function () {
                return response()->json([
                    'message' => "Auth OK",
                ]);
            });
            /**
             * Routes for resource user
             */
            $router->get('user/current', 'UsersController@getCurrent');
            $router->put('user/{id:[0-9]+}', 'UsersController@put');
            $router->delete('user/{id:[0-9]+}', 'UsersController@remove');

            /**
             * Routes for resource board
             */
            $router->post('board', 'BoardsController@add');
            $router->put('board/{id:[0-9]+}', 'BoardsController@put');
            $router->delete('board/{id:[0-9]+}', 'BoardsController@remove');

            /**
             * Routes for resource thread
             */
            $router->post('thread', 'ThreadsController@add');
            $router->put('thread/{id:[0-9]+}', 'ThreadsController@put');
            $router->delete('thread/{id:[0-9]+}', 'ThreadsController@remove');

            /**
             * Routes for resource post
             */
            $router->post('post', 'PostsController@add');
            $router->put('post/{id:[0-9]+}', 'PostsController@put');
            $router->delete('post/{id:[0-9]+}', 'PostsController@remove');
        });
    });
});
