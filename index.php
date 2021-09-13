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

// Basic for googleapis search cost
$router->get('/seed', 'ApiController@seed');
$router->get('/clear', 'ApiController@clear');
$router->get('/search', 'ApiController@search');

$router->get('/ranking', 'DataController@ranking');
$router->get('/universities', 'DataController@universities');
$router->get('/col', 'DataController@costOfLiving');

$router->run();

?>