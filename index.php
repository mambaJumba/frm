<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

define('APP', 'app');
define('CORE', APP . '/core');
define('CFG', 'config');
define('VIEWS', APP . '/views');
define('WWW', 'public');

spl_autoload_register(function ($class) {
	$path = str_replace('\\', '/', $class) . '.php';

	if(file_exists($path)) {
		require $path;
	}
});
if (file_exists('vendor/autoload.php')) {
	require 'vendor/autoload.php';	
}

require CFG . '/routes.php';


