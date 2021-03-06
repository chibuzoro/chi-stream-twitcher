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

$router->get('/auth', 'LoginController'.'@getAuthCodeUrl');
$router->get('/auth/token', 'LoginController'.'@getAccessToken');
$router->get('/auth/response', 'LoginController'.'@respondToClient');
$router->get('/pubsub', 'StreamController'.'@pubsub');
$router->post('/pubsub', 'StreamController'.'@pubsub');

$router->group([ 'middleware' => 'auth'], function($router){
    $router->get('/stream/{channel}', 'StreamController'.'@captureStream');
    $router->post('/stream/subscribe/{channel}', 'StreamController'.'@registerWebHookSubscriptions');
});
