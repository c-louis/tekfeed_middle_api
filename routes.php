<?php

use Buki\Router\Router;

$router = new Router([
    'paths' => [
        'controllers' => 'Controllers',
    ],
    'namespace' => [
        'controllers' => 'Controllers',
    ]
]);

$router->get('/', function() {
    return ['code' => '400', 'msg' => 'Route not served'];
});

// Setup endpoints
$router->group('api-v2', function($router) {
    $router->get('/create', 'ApiController@create');
    $router->get('/seed',   'ApiController@seed');
    $router->get('/clear',  'ApiController@clear');
    $router->get('/search', 'ApiController@search');

    // Data endpoints
    $router->get('/ranking',        'DataController@ranking');
    $router->get('/universities',   'DataController@universities');
    $router->get('/col',            'DataController@costOfLiving');
    $router->get('/themes',            'DataController@themes');
});

$router->group('admin', function($router) {
    // Upload new pdf
    $router->post('/upload', 'AdminController@upload');
    $router->get('/diff', 'AdminController@diff');

    $router->get('/table', 'AdminController@table');
});

$router->get('/epitech/login', 'EpitechController@login');

$router->run();

?>