<?php

header('Access-Control-Allow-Origin: *');

require "bootstrap.php";

use Illuminate\Database\Capsule\Manager as Capsule;
use Buki\Router\Router;
use Symfony\Component\Panther\Client;

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
$router->get('/create', 'ApiController@create');
$router->get('/seed',   'ApiController@seed');
$router->get('/clear',  'ApiController@clear');
$router->get('/search', 'ApiController@search');

// Data endpoints
$router->get('/ranking',        'DataController@ranking');
$router->get('/universities',   'DataController@universities');
$router->get('/col',            'DataController@costOfLiving');

$router->run();

?>