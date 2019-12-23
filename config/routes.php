<?php

use app\core\Router;

$query = rtrim($_SERVER['QUERY_STRING'], '/');

Router::add('/', [
	'controller' => 'MainController',
	'action' => 'index'
]);

Router::add('/register', [
	'controller' => 'AuthController',
	'action' => 'register'
]);

Router::dispatch($query);