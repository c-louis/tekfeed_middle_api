<?php
require 'vendor/autoload.php';

use Buki\Router\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$router = new Router();

$router->get('/', function() {
    return json_encode('Route not served !');
});

$router->get('/search', function(Request $request) {
  
});

$router->run();

?>