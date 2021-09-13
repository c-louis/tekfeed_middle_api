<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
$router->get('/search', 'ApiController@search');
$router->get('/ranking', 'ApiController@ranking');

$router->run();

?>