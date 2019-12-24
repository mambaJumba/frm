<?php

use app\core\base\Router;

$query = rtrim($_SERVER['QUERY_STRING'], '/');

Router::add('/', [
	'controller' => 'MainController',
    'action' => 'index'
]);

Router::add('/register', [
	'controller' => 'AuthController',
	'action' => 'register'
]);

Router::add('/login', [
	'controller' => 'AuthController',
	'action' => 'login'
]);


Router::dispatch($query);