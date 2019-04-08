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

$router->group([ 'prefix' =>'api'], function($router){
    $router->get('/auth', 'LoginController'.'@getAuthCodeUrl');
    $router->get('/auth/token', 'LoginController'.'@getAccessToken');
    $router->get('/auth/response', 'LoginController'.'@respondToClient');
    $router->get('/stream/{channel}', 'StreamController'.'@captureStream');
});

