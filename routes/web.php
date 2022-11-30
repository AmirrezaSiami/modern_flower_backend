<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->group(['prefix' => 'api'], function () use ($router) {

    // Admin routing
    $router->group(['middleware' => 'auth'], function () use ($router) {

        $router->get('/', function () use ($router) {
            return $router->app->version();
        });

        $router->get('user/profile', function () {
            die("dsdasda");
        });

    });

    // Login/Auth routing
    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->post('login', 'AuthUser@login');
    });

});
