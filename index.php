<?php

header('Access-Control-Allow-Origin: *');

require "bootstrap.php";

Use eftec\bladeone\BladeOne;

$views = __DIR__ . '/views';
$cache = __DIR__ . '/cache';
$blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);

@require "routes.php"

?>